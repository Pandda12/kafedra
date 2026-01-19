<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'test@example.com'
            ],
            [
                'first_name' => 'Admin',
                'second_name' => 'User',
                'role' => Roles::ADMIN->value,
                'is_active' => true,
                'auth_step' => 2,
                'password' => Hash::make('Kafedra!2025')
            ]
        );
    }
}
