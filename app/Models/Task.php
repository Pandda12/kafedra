<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasOne};

class Task extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'academic_year_id',
        'description',
        'created_by',
        'starts_on',
        'ends_on',
        'assigned_type',
        'rating'
    ];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date'
    ];

    public function AcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->using(TaskUser::class)
            ->withPivot([
                'status',
                'started_at',
                'rejected_at',
                'completed_at',
                'number_of_rejections'
            ])
            ->withTimestamps();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TaskUser::class, 'task_id');
    }

    public function assignedBy(): hasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function getStatusInfo(int $userId)
    {
        return $this
            ->hasOne(TaskUser::class, 'task_id', 'id')
            ->where('user_id', '=', $userId)
            ->first();
    }

    public static function getDateColor( array $colors, int $daysLeft ): string|null
    {
        foreach ($colors as $color => $days) {
            if ($daysLeft <= $days) {
                return $color;
            }
        }

        return null;
    }
}
