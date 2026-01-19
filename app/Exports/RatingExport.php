<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class RatingExport implements FromArray
{
    protected array $ratings;

    public function __construct( array $ratings )
    {
        $this->ratings = $ratings;
    }

    public function array(): array
    {
        return $this->ratings;
    }
}
