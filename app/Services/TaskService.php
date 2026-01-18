<?php

declare(strict_types=1);

namespace App\Services;

use App\Notifications\TaskAssigned;
use Illuminate\Support\Facades\Notification;
use App\Enums\{Roles, TaskStatus};
use App\Models\{AcademicYear, AcademicYearUser, Task, User};
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TaskService
{
    protected string $adminRole = Roles::ADMIN->value;
    protected string $fullTimeRole = Roles::FULL_TIME_EMPLOYEE->value;
    protected string $partTimeRole = Roles::PART_TIME_EMPLOYEE->value;

    public function store( array $data, string $academicYearSlug )
    {
        $academicYear = $this->getAcademicYear( $academicYearSlug );

        $data['academic_year_id'] = $academicYear->id;
        $data['created_by'] = auth()->id();

        return DB::transaction( function () use ( $data, $academicYear ) {
            $task = Task::create(Arr::only($data, [
                'name',
                'academic_year_id',
                'description',
                'created_by',
                'starts_on',
                'ends_on',
                'assigned_type',
                'rating'
            ]));

            $assigneeIds = $this->resolveAssigneeIds(
                $data['assigned_type'],
                $data['assigned_at'],
                $academicYear
            );

            $this->syncAssignees( $task, $assigneeIds );

            return $task->load(['assignees']);
        });
    }

    public function update( Task $task, array $data, string $academicYearSlug )
    {
        $academicYear = $this->getAcademicYear( $academicYearSlug );

        return DB::transaction( function () use ( $task, $data, $academicYear ) {
            $task->update(Arr::only($data, [
                'name',
                'description',
                'created_by',
                'starts_on',
                'ends_on',
                'assigned_type',
                'rating'
            ]));

            $assigneeIds = $this->resolveAssigneeIds(
                $data['assigned_type'],
                $data['assigned_at'],
                $academicYear
            );

            $this->syncAssignees( $task, $assigneeIds );

            return $task->load(['assignees']);
        });
    }

    protected function getAcademicYear( string $academicYearSlug ): AcademicYear
    {
        return AcademicYear::where('slug', $academicYearSlug)->firstOrFail();
    }

    protected function resolveAssigneeIds( string $type, array $ids, AcademicYear $academicYear ): array
    {
        return match ($type) {
            'all' => AcademicYearUser::query()
                ->where('academic_year_id', $academicYear->id)
                ->pluck('user_id')
                ->all(),
            $this->fullTimeRole => AcademicYearUser::query()
                ->where('academic_year_id', $academicYear->id)
                ->where('role', $this->fullTimeRole)
                ->pluck('user_id')
                ->all(),
            $this->partTimeRole => AcademicYearUser::query()
                ->where('academic_year_id', $academicYear->id)
                ->where('role', $this->partTimeRole)
                ->pluck('user_id')
                ->all(),
            'personal' => array_values( array_unique( array_map('intval', $ids) ) ),
            default => throw new \InvalidArgumentException("Unknown assigned_type: {$type}")
        };
    }

    protected function syncAssignees( Task $task, array $assigneeIds ): void
    {
        $current = $task->assignments()
            ->get([
                'id',
                'task_id',
                'user_id',
                'status',
                'started_at',
                'rejected_at',
                'completed_at',
                'number_of_rejections'
            ])
            ->keyBy('user_id');

        $pivot = [];
        $newUsersIds = [];

        foreach ($assigneeIds as $id) {
            if ( $current->has($id) ) {
                $c = $current[$id];

                $pivot[$id] = [
                    'status' => $c->status,
                    'started_at' => $c->started_at,
                    'rejected_at' => $c->rejected_at,
                    'completed_at' => $c->completed_at,
                    'number_of_rejections' => $c->number_of_rejections
                ];
            } else {
                $pivot[$id] = [
                    'status' => TaskStatus::ASSIGNED->value,
                    'number_of_rejections' => 0
                ];

                $newUsersIds[] = $id;
            }
        }

        $task->assignees()->sync($pivot);

        if ( !empty($newUsersIds) ) {
            $newUsers = User::whereIn('id', $newUsersIds)->get();

            Notification::send($newUsers, new TaskAssigned($task));
        }
    }
}