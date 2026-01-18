<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PublicationType;
use Illuminate\Database\Seeder;

class PublicationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publicationTypes = [
            ['name' => 'Тезисы доклада в РБ'],
            ['name' => 'Тезисы доклада за рубежом'],
            ['name' => 'Статья в сборнике статей в РБ'],
            ['name' => 'Статья в сборнике статей за рубежом'],
            ['name' => 'Статья ВАК в РБ'],
            ['name' => 'Статья ВАК за рубежом'],
            ['name' => 'Монография в РБ'],
            ['name' => 'Монография за рубежом'],
            ['name' => 'Статья Scopus/WoS в РБ'],
            ['name' => 'Статья Scopus/WoS за рубежом'],
            ['name' => 'Учебное пособие с грифом УМО'],
            ['name' => 'Учебное пособие с грифом МО']
        ];

        foreach ($publicationTypes as $publicationType) {
            PublicationType::updateOrCreate($publicationType);
        }
    }
}
