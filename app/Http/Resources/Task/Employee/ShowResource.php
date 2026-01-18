<?php

declare(strict_types=1);

namespace App\Http\Resources\Task\Employee;

use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( Request $request ): array
    {
        $user = $request->user();
        $userTaskInfo = $this->getStatusInfo($user->id);

        switch ($userTaskInfo->status->value) {
            case TaskStatus::ASSIGNED->value:
                $showUploadForm = false;
                $btnText = 'Взять в работу';
                $action = TaskStatus::ACCEPTED->value;
                break;
            case TaskStatus::ACCEPTED->value:
            case TaskStatus::REVISED->value:
                $showUploadForm = true;
                $btnText = 'Отправить на проверку';
                $action = TaskStatus::REVIEW->value;
                break;
            case TaskStatus::REJECTED->value:
                $showUploadForm = false;
                $btnText = 'Взять на доработку';
                $action = TaskStatus::REVISED->value;
                break;
            default:
                $showUploadForm = false;
                $btnText = false;
                $action = false;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'starts_on' => $this->starts_on->format("d.m.Y"),
            'ends_on' => $this->ends_on->format("d.m.Y"),
            'status' => [
                'name' => $userTaskInfo->status->label(),
                'value' => $userTaskInfo->status->value,
            ],
            'show_upload_form' => $showUploadForm,
            'number_of_rejections' => $userTaskInfo->number_of_rejections,
            'reject_reason' => $userTaskInfo->reject_reason,
            'rating' => $this->rating,
            'action' => [
                'btn_text' => $btnText,
                'url' => route('employee.tasks.update', $this->id),
                'status' => $action,
            ]
        ];
    }
}
