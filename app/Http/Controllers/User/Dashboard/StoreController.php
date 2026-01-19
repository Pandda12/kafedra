<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Dashboard\StoreRequest;
use App\Models\User;
use Faker\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( StoreRequest $request ): RedirectResponse
    {
        $data = $request->validated();

        User::create([
            'first_name' => $data['first_name'],
            'second_name' => $data['second_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? Factory::create()->unique()->safeEmail(),
            'role' => $data['role'],
            'is_active' => $data['is_active'],
            'auth_step' => 1,
            'password' => Str::password()
        ]);

        return to_route('dashboard.user.index');
    }
}
