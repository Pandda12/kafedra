<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    protected $fillable = [
        'name',
        'academic_year_id',
        'author',
        'co_author',
        'publication_type_id',
        'name_of_scientific_event',
        'publisher',
        'year',
        'pages',
        'DOI_url',
        'bibliographic_description',
        'repository_url',
        'file_id',
        'user_id'
    ];

    public function publicationType(): BelongsTo
    {
        return $this->belongsTo(PublicationType::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
