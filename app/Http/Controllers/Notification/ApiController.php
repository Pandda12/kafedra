<?php

declare(strict_types=1);

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\{JsonResponse, Request};

class ApiController extends Controller
{
    public function getAdminNotifications( Request $request ): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'data' => $user->unreadNotifications
                ->map( function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => class_basename($notification->type),
                        'data' => $notification->data,
                        'created_at'=> $notification->created_at->toIso8601String()
                    ];
                })
                ->values()
                ->all()
        ]);
    }
}
