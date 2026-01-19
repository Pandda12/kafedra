<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Dashboard;

use App\Actions\GetDashboardRatingChartDataAction;
use App\DTO\DashboardRatingChartParams;
use App\Enums\{TaskStatus, UserSelectType};
use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Event, File, Publication, Task, TaskUser};
use App\Notifications\TaskAssigned;
use App\Services\TaskChartService;
use Carbon\Carbon;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Notification, Storage};

class ApiController extends Controller
{
    public function getDashboardTasksChartData( Request $request, TaskChartService $service, string $slug ): JsonResponse
    {
        $data = $service->getAdminTaskChartData($request, $slug);

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getEmployeeTasksChartData( Request $request, TaskChartService $service ): JsonResponse
    {
        $data = $service->getEmployeeTasksChartData($request);

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getDashboardRatingChartData(
        Request $request,
        string $academicYearSlug,
        GetDashboardRatingChartDataAction $action
    ): JsonResponse
    {
        $params = new DashboardRatingChartParams(
            assignedType: (string) $request->get('assigned_type'),
            assignedAt: (array) $request->get('assigned_at', []),
            month: (int) ($request->get('month') ?? now()->month),
            academicYearSlug: $academicYearSlug
        );

        $data = $action->execute($params);

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function getEmployeeRatingChartData( Request $request ): JsonResponse
    {
        $user = $request->user();
        $month = $request->month ?? Carbon::now()->month;
        $academicYear = AcademicYear::getActiveYear();

        $tasks = TaskUser::query()
            ->select([
                'tasks.id',
                'tasks.name',
                'tasks.rating',
                'tasks.ends_on',
                'task_user.completed_at'
            ])
            ->where([
                'task_user.user_id' => $user->id,
                'task_user.status' => TaskStatus::CLOSED->value,
                'tasks.academic_year_id' => $academicYear->id
            ])
            ->whereMonth('task_user.completed_at', $month)
            ->join('tasks', 'tasks.id', '=', 'task_user.task_id')
            ->get()
            ->map( fn ($task) => [
                'name' => ['Задача: ', $task->name],
                'rating' => $task->ends_on < $task->completed_at ? 0 - $task->rating : $task->rating
            ])
            ->toArray();

        $publications = Publication::query()
            ->where([
                'user_id' => $user->id,
                'academic_year_id' => $academicYear->id
            ])
            ->whereMonth('created_at', $month)
            ->get()
            ->map( fn ($publication) => [
                'name' => ['Публикация: ', $publication->name],
                'rating' => $academicYear->settings['publication_rating'] ?? 0
            ])
            ->toArray();

        $events = Event::query()
            ->where([
                'user_id' => $user->id,
                'academic_year_id' => $academicYear->id
            ])
            ->whereMonth('created_at', $month)
            ->get()
                ->map(fn ($event) => [
                'name' => ['Мероприятие: ', $event->name],
                'rating' => $academicYear->settings['event_rating'] ?? 0
            ])
            ->toArray();

        $user_data = array_merge($tasks, $publications, $events);

        $data = [];

        foreach ($user_data as $item) {
            $data['items'][] = $item['name'];
            $data['rating'][] = $item['rating'];
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function updateTaskStatus( Request $request, Task $task ): JsonResponse
    {
        $data = $request->validate([
            'task_user_id' => ['required', 'integer', 'exists:task_user,id'],
            'status' => ['required', 'string'],
            'reject_reason' => ['nullable', 'string']
        ]);

        $taskUser = TaskUser::findOrFail($data['task_user_id']);

        switch( $data['status'] ) {
            case TaskStatus::ASSIGNED->value:
                $taskUser->update([
                    'status' => TaskStatus::ASSIGNED->value
                ]);

                Notification::send([$taskUser->user()->first()], new TaskAssigned($task));

                $status = true;

                break;
            case TaskStatus::REJECTED->value:
                $taskUser->update([
                    'status' => TaskStatus::REJECTED->value,
                    'rejected_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'number_of_rejections' => $taskUser->number_of_rejections + 1,
                    'reject_reason' => $data['reject_reason']
                ]);

                $file = $taskUser->file;

                if ( $file ) {
                    Storage::delete($file->path);
                    $file->delete();
                }

                Notification::send([$taskUser->user()->first()], new TaskAssigned($task));

                $status = true;

                break;
            case TaskStatus::CLOSED->value:
                $taskUser->update([
                    'status' => TaskStatus::CLOSED->value,
                    'completed_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);

                $status = true;

                break;
            default:
                $status = false;
        }

        return response()->json([
            'status' => $status
        ]);
    }

    public function getDashboardPublicationsChartData( Request $request, string $slug ): JsonResponse
    {
        $academicYear = AcademicYear::where('slug', $slug)->firstOrFail();

        $assignedType = $request->input('assigned_type');
        $assignedAt = (array) $request->input('assigned_at', []);

        $query = Publication::query()
            ->join('users as u', 'publications.user_id', '=', 'u.id')
            ->where('publications.academic_year_id', $academicYear->id)
            ->groupBy('publications.user_id', 'u.id')
            ->selectRaw('COUNT(*) as total, publications.user_id, u.first_name, u.second_name');

        switch ( $assignedType ) {
            case UserSelectType::FULL_TIME->value:
            case UserSelectType::PART_TIME->value:
                $query->join('academic_year_user as ayu', 'publications.user_id', '=', 'ayu.user_id')
                    ->where('ayu.role', $assignedType);
                break;

            case UserSelectType::PERSONAL->value:
                if ( !empty($assignedAt) ) {
                    $query->whereIn('u.id', $assignedAt);
                }
                break;
        }

        $publications = $query->get();

        $data = [];

        foreach ( $publications as $publication ) {
            $data['users'][] = $publication->first_name . ' ' . $publication->second_name;
            $data['count'][] = $publication->total;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function getDashboardEventsChartData( Request $request, string $slug ): JsonResponse
    {
        $academicYear = AcademicYear::where('slug', $slug)->firstOrFail();

        $assignedType = $request->input('assigned_type');
        $assignedAt = (array)$request->input('assigned_at', []);

        $query = Event::query()
            ->join('users as u', 'events.user_id', '=', 'u.id')
            ->where('events.academic_year_id', $academicYear->id)
            ->groupBy('events.user_id', 'u.id')
            ->selectRaw('COUNT(*) as total, events.user_id, u.first_name, u.second_name');

        switch ( $assignedType ) {
            case UserSelectType::FULL_TIME->value:
            case UserSelectType::PART_TIME->value:
                $query->join('academic_year_user as ayu', 'events.user_id', '=', 'ayu.user_id')
                    ->where('ayu.role', $assignedType);
                break;

            case UserSelectType::PERSONAL->value:
                if ( !empty($assignedAt) ) {
                    $query->whereIn('u.id', $assignedAt);
                }
                break;
        }

        $events = $query->get();

        $data = [];

        foreach ( $events as $event ) {
            $data['users'][] = $event->first_name . ' ' . $event->second_name;
            $data['count'][] = $event->total;
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function getUserReports( Request $request, string $academicYearSlug ): JsonResponse
    {
        $data = $request->validate([
            'user' => ['required', 'integer', 'exists:users,id'],
            'status' => ['required', 'string']
        ]);

        $taskUsers = TaskUser::query()
            ->whereNot('status', TaskStatus::PROPOSED->value)
            ->where('user_id', $data['user'])
            ->where('status', $data['status'])
            ->get()
            ->map(function($taskUser) {
                $task = $taskUser->task;

                $type = match($taskUser->status->value) {
                    TaskStatus::ASSIGNED->value,
                    TaskStatus::ACCEPTED->value,
                    TaskStatus::REVISED->value,
                    TaskStatus::REVIEW->value => 'in_progress',
                    TaskStatus::REJECTED->value,
                    TaskStatus::CLOSED->value => 'closed',
                    default => $taskUser->status
                };

                $reportFile = $taskUser->report_file_id ? [
                    'id' => $taskUser->report_file_id,
                    'name' => $taskUser->file->name,
                    'extension' => $taskUser->file->extension,
                    'size' => File::bytesToHuman( $taskUser->file->size )
                ] : null;

                return [
                    'type' => $type,
                    'task' => [
                        'id' => $task->id,
                        'name' => $task->name,
                        'status' => $taskUser->status->label(),
                        'starts_on' => $task->starts_on->format("d.m.Y"),
                        'ends_on' => $task->ends_on->format("d.m.Y"),
                        'description' => $task->description,
                        'started_at' => $taskUser->started_at?->format("d.m.Y"),
                        'rejected_at' => $taskUser->rejected_at?->format("d.m.Y"),
                        'completed_at' => $taskUser->completed_at?->format("d.m.Y"),
                        'report_text' => $taskUser->report_text,
                        'report_file' => $reportFile
                    ],
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $taskUsers
        ]);
    }
}