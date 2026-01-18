<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\{EnsuresSingleActive, HasSlug};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

class AcademicYear extends Model
{
    use EnsuresSingleActive, HasSlug;

    const DEFAULT_SETTINGS = [
        'task_date_color' => [
            'red' => 3,
            'yellow' => 5,
            'green' => 10
        ],
        'publication_rating' => 10,
        'event_rating' => 10
    ];

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'starts_on',
        'ends_on',
        'description',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array'
    ];

    public function scopeActive($q) { return $q->where('is_active', true); }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    public function isUserAssigned( int $userId ): bool
    {
        return AcademicYearUser::where([
            'academic_year_id' => $this->id,
            'user_id' => $userId
        ])->exists();
    }

    public static function getActiveYear()
    {
        $year = self::where('is_active', true)->first();

        return $year ?? null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

}
