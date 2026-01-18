<?php

declare(strict_types=1);

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notification\UpdateRequest;
use App\Models\TaskUser;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( UpdateRequest $request, string $notificationId ): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $notification = $user->notifications()->findOrFail($notificationId);

        if ( !$user->isAdmin() ) {

            $userTask = TaskUser::find($data['task_id']);

            if ( $userTask ) {
                $userTask->update([
                    'status' => $data['status']
                ]);
            }
        }

        $notification->markAsRead();

        return response()->json([
            'status' => true
        ]);
    }
}
