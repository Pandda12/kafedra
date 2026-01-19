<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, User};
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Validation\{Rule, Rules, ValidationException};
use Inertia\{Inertia, Response};

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    public function checkUser( Request $request ): JsonResponse
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'second_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255']
        ]);

        $user = User::where([
            'first_name' => $data['first_name'],
            'second_name' => $data['second_name'],
            'last_name' => $data['last_name']
        ])->first();

        return response()->json([
            'status' => (bool)$user,
            'data' => $user ? [
                'user_id' => $user->id
            ] : null
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store( Request $request ): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'max:255', 'exists:users,id'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($request->id)
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::find($data['id']);

        $user->update([
            'email' => $data['email'],
            'auth_step' => 2,
            'password' => Hash::make($data['password'])
        ]);

        event(new Registered($user));

        $activeAcademicYear = AcademicYear::getActiveYear();
        $isMember = $activeAcademicYear->isUserAssigned($user->id);

        if ( $user->isEmployee() && !$isMember  ) {
            throw ValidationException::withMessages([
                'email' => 'Ваш аккаунт зарегистоирован, но у вас нет доступа к текущему активному году.'
            ]);
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
