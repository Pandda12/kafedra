<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Dashboard\UpdateRequest;
use App\Models\User;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdateController extends Controller
{
    public function profileUpdate( UpdateRequest $request, User $user ): RedirectResponse
    {
        $data = $request->validated();

        $user->update($data);

        return back();
    }

    public function passwordUpdate( Request $request, User $user ): RedirectResponse
    {
        $data = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed']
        ]);

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return back();
    }
}
