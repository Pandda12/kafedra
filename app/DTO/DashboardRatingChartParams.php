<?php

declare(strict_types=1);

namespace App\DTO;

final class DashboardRatingChartParams
{
    public function __construct(
        public readonly string $assignedType,
        /** @var int[] */
        public readonly array $assignedAt,
        public readonly int $month,
        public readonly string $academicYearSlug,
    ) {}
}
