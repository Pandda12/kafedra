<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\TaskStatus;
use App\Models\{Task, TaskUser};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskProposed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct( public Task $task, public  TaskUser $taskUser) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
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
    public function toArray(object $notifiable): array
    {
        return [
            'year' => [
                'name' => $this->task->AcademicYear->name,
                'url' => route('dashboard.academic_year.show', $this->task->AcademicYear->slug)
            ],
            'task' => [
                'id' => $this->task->id,
                'task_user_id' => $this->taskUser->id,
                'name' => $this->task->name,
                'description' => $this->task->description,
                'starts_on' => $this->task->starts_on->format('d.m.Y'),
                'ends_on' => $this->task->ends_on->format('d.m.Y'),
                'url' => route('dashboard.task.edit', [
                    $this->task->AcademicYear->slug,
                    $this->task->id
                ])
            ],
            'user' => [
                'id' =>  $this->taskUser->user->id,
                'name' =>  $this->taskUser->user->getFullName()
            ],
            'status' => [
                'assigned' => TaskStatus::ASSIGNED->value,
                'delete' => TaskStatus::DELETE->value
            ],
        ];
    }
}
