<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Roles;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, Pivot};

class AcademicYearUser extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'academic_year_id',
        'user_id',
        'role'
    ];

    protected $casts = [
        'role' => Roles::class
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}