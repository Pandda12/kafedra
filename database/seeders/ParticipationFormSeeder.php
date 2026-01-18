<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ParticipationForm;
use Illuminate\Database\Seeder;

class ParticipationFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $participationForms = [
            ['name' => 'Выступление с докладом и публикациями'],
            ['name' => 'Выступление с докладом без публикации'],
            ['name' => '(со)организация мероприятия'],
            ['name' => 'В качестве эксперта/жюри'],
            ['name' => 'В качестве слушателя/посетителя'],
            ['name' => 'Иное']
        ];

        foreach ($participationForms as $participationForm) {
            ParticipationForm::updateOrCreate($participationForm);
        }
    }
}
