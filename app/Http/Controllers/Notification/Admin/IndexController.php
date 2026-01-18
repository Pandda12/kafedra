<?php

declare(strict_types=1);

namespace App\Http\Controllers\Notification\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Inertia\{Inertia, Response};
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $user = $request->user();

        return Inertia::render('Dashboard/Notification/Index', [
            'notifications' => Notification::getAdminNotifications($user)
        ]);
    }
}
