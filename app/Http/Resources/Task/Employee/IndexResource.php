<?php

declare(strict_types=1);

namespace App\Http\Resources\Task\Employee;

use App\Models\AcademicYear;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    protected ?AcademicYear $activeYear = null;

    public function withActiveYear(AcademicYear $year): self
    {
        $this->activeYear = $year;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $userTaskInfo = $this->getStatusInfo($user->id);
        $academicYearSettings = $this->activeYear->settings;
        $daysDiff = (int)now()->startOfDay()->diffInDays($this->ends_on->startOfDay());
        $dataColor = Task::getDateColor($academicYearSettings['task_date_color'], $daysDiff);
        $closedOnTime = !($this->ends_on < $userTaskInfo->completed_at);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => [
                'value' => $userTaskInfo->status->value,
                'name' => $userTaskInfo->status->label(),
                'completed_at' => $userTaskInfo->completed_at?->format('d.m.Y'),
                'closed_on_time' => $closedOnTime,
                'closed_status' => $closedOnTime ? 'Закрыта в срок' : 'Закрыта не в срок'
            ],
            'color' => $dataColor,
            'starts_on' => $this->starts_on->format('d.m.Y'),
            'ends_on' => $this->ends_on->format('d.m.Y'),
            'rating' => $closedOnTime ? $this->rating : 0 - $this->rating
        ];
    }
}
