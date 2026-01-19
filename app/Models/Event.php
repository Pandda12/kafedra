<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'name',
        'academic_year_id',
        'place',
        'date',
        'participation_form_id',
        'title_of_the_report',
        'user_id'
    ];

    public function participationForm(): BelongsTo
    {
        return $this->belongsTo(ParticipationForm::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
