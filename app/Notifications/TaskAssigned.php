<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\TaskUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct( public Task $task ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via( object $notifiable ): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray( object $notifiable ): array
    {
        $assignedBy = $this->task->assignedBy()->first();

        $userTask = TaskUser::where([
            'task_id' => $this->task->id,
            'user_id' => $notifiable->id,
        ])->firstOrFail();

        switch ($userTask->status->value) {
            case TaskStatus::ASSIGNED->value:
            default:
                $text = "Пользователь {$assignedBy->first_name} {$assignedBy->second_name} назначил";
                $btnText = 'Взять в работу';
                $action = TaskStatus::ACCEPTED->value;
                break;
            case TaskStatus::REJECTED->value:
                $text = "Пользователь {$assignedBy->first_name} {$assignedBy->second_name} отклонил";
                $btnText = 'Взять на доработку';
                $action = TaskStatus::REVISED->value;
                break;
        }

        return [
            'task' => [
                'id' => $this->task->id,
                'task_user_id' => $userTask->id,
                'name' => $this->task->name,
                'description' => $this->task->description,
                'starts_on' => $this->task->starts_on->format('d.m.Y'),
                'ends_on' => $this->task->ends_on->format('d.m.Y'),
                'rating' => $this->task->rating,
                'reject_reason' => $userTask->reject_reason,
                'status' => $userTask->status->value
            ],
            'text' => $text,
            'btn_text' => $btnText,
            'action' => $action,
            'open_url' => route('employee.tasks.show', $this->task->id)
        ];
    }
}
