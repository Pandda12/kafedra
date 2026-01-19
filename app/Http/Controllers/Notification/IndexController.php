<?php

declare(strict_types=1);

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Models\TaskUser;
use Illuminate\Http\{JsonResponse, Request};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'data' => $user->unreadNotifications
                ->map( function ($notification) {

                    $taskUser = TaskUser::find($notification->data['task']['task_user_id']);

                    if ( $taskUser ) {
                        $isActionable = $taskUser->status->value === $notification->data['task']['status'];
                    } else {
                        $isActionable = false;
                    }

                    return [
                        'id' => $notification->id,
                        'type' => class_basename($notification->type),
                        'data' => [
                            ...$notification->data,
                            'is_actionable' => $isActionable,
                            'update_url' => route('employee.api.notification.update', $notification->id)
                        ],
                        'created_at'=> $notification->created_at->toIso8601String()
                    ];
                })
                ->values()
                ->all()
        ]);
    }
}
