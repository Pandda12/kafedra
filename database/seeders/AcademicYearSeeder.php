<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AcademicYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = AcademicYear::DEFAULT_SETTINGS;

        AcademicYear::updateOrCreate(
            [
                'slug' => Str::slug('2025–2026')
            ],
            [
                'name' => '2025–2026',
                'starts_on' => '2025-09-01',
                'ends_on' => '2026-05-31',
                'is_active' => true,
                'settings' => $settings
            ]
        );

        AcademicYear::updateOrCreate(
            [
                'slug' => Str::slug('2024–2025')
            ],
            [
                'name' => '2024–2025',
                'starts_on' => '2024-09-01',
                'ends_on' => '2025-05-31',
                'is_active' => false,
                'settings' => $settings
            ]
        );
    }
}
