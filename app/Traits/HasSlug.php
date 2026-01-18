<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ( $model ) {
            $model->slug = $model->generateUniqueSlug( $model->name );
        });

        static::updating(function ( $model)  {
            if ( $model->isDirty('name') ) {
                $model->slug = $model->generateUniqueSlug( $model->name );
            }
        });
    }

    public function generateUniqueSlug( $name ): string
    {
        $baseSlug = Str::slug(Str::lower( $name ));
        $slug = $baseSlug;
        $count = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        return $slug;
    }
}