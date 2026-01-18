<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public static function getAdminNotifications( User $user ): array
    {
        return $user->unreadNotifications
            ->map( function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => class_basename($notification->type),
                    'data' => $notification->data,
                    'created_at'=> $notification->created_at->toIso8601String()
                ];
            })
            ->values()
            ->all();
    }
}
