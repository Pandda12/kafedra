<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait EnsuresSingleActive
{
    protected static function bootEnsuresSingleActive(): void
    {
        static::creating(function ($model) {
            if ( self::shouldDeactivateOthers($model) ) {
                self::deactivateOthers($model);
            }
        });

        static::updating(function ($model) {
            if ( self::shouldDeactivateOthers($model) ) {
                self::deactivateOthers($model);
            }
        });
    }

    protected static function shouldDeactivateOthers( $model ): bool
    {
        return $model->isDirty('is_active') && (bool) $model->is_active === true;
    }

    protected static function deactivateOthers( $model ): void
    {
        $table = $model->getTable();
        $pkName = $model->getKeyName();
        $pkValue = $model->getKey();

        $query = DB::table($table)->where('is_active', true);

        if ( $model->exists && $pkValue !== null ) {
            $query->where($pkName, '<>', $pkValue);
        }

        $query->update(['is_active' => false]);
    }
}