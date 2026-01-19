<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\{RedirectResponse, Request};

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, User $user ): RedirectResponse
    {
        $user->delete();

        return to_route('dashboard.user.index');
    }
}
