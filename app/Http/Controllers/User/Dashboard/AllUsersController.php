<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class AllUsersController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $users = User::orderBy('second_name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->getFullName(),
                    'email' => $user->auth_step === 2 ? $user->email : '-',
                    'role' => $user->role->label(),
                    'is_active' => $user->is_active
                ];
            });

        return Inertia::render('Dashboard/Users/AllUsers', [
            'users' => $users
        ]);
    }
}
