<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = [
        'name',
        'disk',
        'path',
        'extension',
        'size',
        'meta'
    ];

    public static function generateFilename( string $directory, string $basename, string $extension ): string
    {
        $filename = "{$basename}.{$extension}";
        $counter  = 0;

        while (
            Storage::disk('local')->exists("{$directory}/{$filename}") ||
            self::where('name', "{$directory}/{$filename}")->exists()
        ) {
            $counter++;
            $filename = "{$basename}-{$counter}.{$extension}";
        }

        return $filename;
    }

    public static function bytesToHuman( int $bytes ): string
    {
        $units = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ', 'ПБ'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round( $bytes, 2 ) . ' ' . $units[$i];
    }
}
