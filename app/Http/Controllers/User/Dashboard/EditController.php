<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dashboard;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, User $user ): Response
    {
        $data = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'second_name' => $user->second_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'role' => $user->role->value,
            'is_active' => $user->is_active,
            'auth_step' => $user->auth_step,
            'notice' => $user->auth_step !== 2 ? 'Пользователь ещё не зарегистрирован на платформе' : null
        ];

        return Inertia::render('Dashboard/Users/Edit', [
            'user' => $data,
            'roles' => Roles::getCreateRoles()
        ]);
    }
}
