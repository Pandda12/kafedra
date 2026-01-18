<?php

declare(strict_types=1);

namespace App\Imports;

use App\Enums\Roles;
use App\Models\{AcademicYearUser, User};
use Faker\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    private int $academicYearId;
    private string $role;

    public function __construct( int $academicYearId, string $role )
    {
        $this->academicYearId = $academicYearId;
        $this->role = $role;
    }

    /**
     * @param Collection $rows
     *
     * @return void
     */
    public function collection( Collection $rows ): void
    {
        foreach ( $rows as $row ) {

            if ( !$row[0] ) {
                continue;
            }

            $fullName = explode(" ", $row[0]);

            try {
                $user = User::updateOrCreate(
                    [
                        'first_name' => $fullName[1],
                        'second_name' => $fullName[0],
                        'last_name' => $fullName[2] ?? null
                    ],
                    [
                        'email' => Factory::create()->unique()->safeEmail(),
                        'position' => $row[1],
                        'role' => Roles::EMPLOYEE->value,
                        'is_active' => true,
                        'auth_step' => 1,
                        'password' => Str::password()
                    ]
                );

                $assigned = AcademicYearUser::updateOrCreate(
                    [
                        'academic_year_id' => $this->academicYearId,
                        'user_id' => $user->id
                    ],
                    [
                        'role' => $this->role
                    ]
                );
            } catch ( \Exception $e ) {
                Log::error( $e->getMessage() );
            }
        }
    }
}
