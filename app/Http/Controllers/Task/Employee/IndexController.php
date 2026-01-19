<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Employee;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Task\Employee\IndexResource;
use App\Models\{AcademicYear, Task};
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $taskStatus = $request->input('status');
        $activeAcademicYear = AcademicYear::getActiveYear();

        $tasks = Task::query()
            ->whereHas('assignees', function($query) use ($taskStatus) {
                $query->where('users.id', auth()->id());
                $query->whereNot('task_user.status', TaskStatus::PROPOSED->value);

                if ( $taskStatus === 'closed' ) {
                    $query->where('task_user.status', TaskStatus::CLOSED->value);
                } else {
                    $query->whereNot('task_user.status', TaskStatus::CLOSED->value);
                }

            })
            ->where('tasks.academic_year_id', $activeAcademicYear->id)
            ->orderBy('tasks.ends_on')
            ->get();

        $tasksArray = $tasks->map(fn ($task) =>
        (new IndexResource($task))
            ->withActiveYear($activeAcademicYear)
            ->resolve()
        )->all();

        return Inertia::render('Employee/Task/Index', [
            'tasks' => $tasksArray
        ]);
    }
}
