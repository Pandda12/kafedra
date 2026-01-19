<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Roles;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\{HasFactory};
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'email',
        'position',
        'role',
        'is_active',
        'auth_step',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Roles::class
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role instanceof Roles
            ? $this->role === Roles::ADMIN
            : (string) $this->role === Roles::ADMIN->value;
    }

    public function isEmployee(): bool
    {
        return $this->role instanceof Roles
            ? $this->role === Roles::EMPLOYEE
            : (string) $this->role === Roles::EMPLOYEE->value;
    }

    public function getFullName(): string
    {
        $name = $this->second_name . ' ' . $this->first_name;

        if ( $this->last_name ) {
            $name .= ' ' . $this->last_name;
        }

        return $name;
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

    public static function getAllEmployees(): Collection|\Illuminate\Support\Collection
    {
        $activeAcademicYear = AcademicYear::getActiveYear();

        return AcademicYearUser::query()
            ->select(
                'academic_year_id', 'user_id', 'academic_year_user.role', 'users.first_name', 'users.second_name', 'users.last_name'
            )
            ->join('users', 'academic_year_user.user_id', '=', 'users.id')
            ->where('academic_year_user.academic_year_id', '=', $activeAcademicYear->id)
            ->orderBy('users.second_name')
            ->get()
            ->map(function($user) {
                $name = $user->second_name . ' ' . $user->first_name;

                if ( $user->last_name ) {
                    $name .= ' ' . $user->last_name;
                }

                return [
                    'value' => $user->user_id,
                    'label' => $name,
                    'role' => $user->role,
                ];
            });
    }
}
