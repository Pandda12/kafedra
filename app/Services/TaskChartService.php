<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Enums\UserSelectType;
use App\Models\{AcademicYear, Task, User};

class TaskChartService
{
    public function getAdminTaskChartData( Request $request, string $academicYearSlug ): array
    {
        $academicYear = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();

        $assignedType = $request->input('assigned_type');
        $assignedAt = (array) $request->input('assigned_at', []);

        $taskQuery = $this->getQuery($academicYear, $assignedType, $assignedAt);

        $taskQuery
            ->whereHas('assignees', function($q){
                $q->whereNot('status', TaskStatus::CLOSED->value);
                $q->whereNot('status', TaskStatus::PROPOSED->value);
            });

        $tasks = $taskQuery->get();

        if ( empty($taskQuery) ) {
            return [];
        }

        return $tasks->map(function (Task $task) use ($academicYear) {
            $users = $task->assignees;

            return [
                'name' => $task->name,
                'data' => $users->map(function (User $user) use ($academicYear, $task) {
                    return [
                        'x' => trim($user->first_name . ' ' . $user->second_name),
                        'y' => [
                            $task->starts_on->startOfDay()->valueOf(),
                            $task->ends_on->endOfDay()->valueOf(),
                        ],
                        'url' => route('dashboard.task.edit', [
                            'academicYear' => $academicYear,
                            'task' => $task
                        ]),
                    ];
                })->values()->all(),
            ];
        })->values()->all();
    }

    public function getEmployeeTasksChartData( Request $request ): array
    {
        $user = $request->user();
        $academicYear = AcademicYear::getActiveYear();

        $assignedType = UserSelectType::PERSONAL->value;
        $assignedAt = [$user->id];

        $taskQuery = $this->getQuery($academicYear, $assignedType, $assignedAt);
        $taskQuery
            ->whereHas('assignees', function($q) use ($user) {
                $q->where('user_id', $user->id);
                $q->whereNotIn('status', [TaskStatus::CLOSED->value, TaskStatus::PROPOSED->value]);
            });

        $tasks = $taskQuery->get();

        if ( empty($taskQuery) ) {
            return [];
        }

        return $tasks->map(function (Task $task) use ($academicYear) {
            $users = $task->assignees;

            return [
                'name' => $task->name,
                'data' => $users->map(function (User $user) use ($academicYear, $task) {
                    return [
                        'x' => $task->name,
                        'y' => [
                            $task->starts_on->startOfDay()->valueOf(),
                            $task->ends_on->endOfDay()->valueOf(),
                        ],
                        'url' => route('employee.tasks.show', $task),
                    ];
                })->values()->all(),
            ];
        })->values()->all();
    }

    protected function getQuery( AcademicYear $academicYear, ?string $assignedType, array $assignedAt ): Builder|array
    {
        $tasksQuery = Task::query()
            ->select(['tasks.id', 'tasks.name', 'tasks.starts_on', 'tasks.ends_on'])
            ->where('tasks.academic_year_id', $academicYear->id);

        switch ( $assignedType ) {
            case UserSelectType::FULL_TIME->value:
            case UserSelectType::PART_TIME->value:

                $tasksQuery
                    ->join('academic_year_user as ayu', 'tasks.academic_year_id', '=', 'ayu.academic_year_id')
                    ->where('ayu.role', $assignedType)
                    ->groupBy('tasks.id');
                break;

            case UserSelectType::PERSONAL->value:
                if ( !empty($assignedAt) ) {
                    $tasksQuery
                        ->whereHas('assignees', fn ($q) => $q->whereIn('users.id', $assignedAt))
                        ->with(['assignees' => fn ($q) => $q->whereIn('users.id', $assignedAt)]);
                } else {
                    return [];
                }
                break;

            default:
                $tasksQuery->with('assignees');

                break;
        }

        return $tasksQuery;
    }
}