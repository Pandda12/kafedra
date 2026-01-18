<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dashboard;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        return Inertia::render('Dashboard/Users/Create', [
            'roles' => Roles::getCreateRoles()
        ]);
    }
}
