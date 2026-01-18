<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipationForm extends Model
{
    protected $fillable = ['name', 'is_active'];
}
