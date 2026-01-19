<?php

declare(strict_types=1);

namespace App\Http\Controllers\Home\Employee;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Event, Publication, Task};
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $user = $request->user();
        $academicYear = AcademicYear::getActiveYear();

        $activeTasks = Task::query()
            ->where([
                'tasks.academic_year_id' => $academicYear->id,
                'task_user.user_id' => $user->id,
            ])
            ->whereNot('task_user.status', TaskStatus::CLOSED->value)
            ->join('task_user', 'task_user.task_id', '=', 'tasks.id')
            ->getCountForPagination();

        $closedTasks = Task::query()
            ->where([
                'tasks.academic_year_id' => $academicYear->id,
                'task_user.user_id' => $user->id,
                'task_user.status' => TaskStatus::CLOSED->value
            ])
            ->join('task_user', 'task_user.task_id', '=', 'tasks.id')
            ->getCountForPagination();

        $publications = Publication::where([
            'academic_year_id' => $academicYear->id,
            'user_id' => $user->id
        ])->getCountForPagination();

        $events = Event::where([
            'academic_year_id' => $academicYear->id,
            'user_id' => $user->id
        ])->getCountForPagination();

        return Inertia::render('Home', [
            'active_tasks_count' => $activeTasks,
            'closed_tasks_count' => $closedTasks,
            'publications_count' => $publications,
            'events_count' => $events
        ]);
    }
}
