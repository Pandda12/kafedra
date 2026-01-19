<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Employee;

use Illuminate\Support\Facades\Notification;
use App\Enums\{Roles, TaskStatus};
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\Employee\StoreRequest;
use App\Models\{AcademicYear, Task, TaskUser, User};
use App\Notifications\TaskProposed;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( StoreRequest $request ): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $activeAcademicYear = AcademicYear::getActiveYear();

        $task = Task::create([
            'name' => $data['name'],
            'academic_year_id' => $activeAcademicYear->id,
            'description' => $data['description'],
            'created_by' => $user->id,
            'starts_on' => $data['starts_on'],
            'ends_on' => $data['ends_on'],
            'assigned_type' => 'personal',
            'rating' => 0
        ]);

        $taskUser = TaskUser::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'status' => TaskStatus::PROPOSED->value
        ]);

        $adminUsers = User::where('role', Roles::ADMIN->value)->get();
        Notification::send($adminUsers, new TaskProposed($task, $taskUser));

        return to_route('employee.tasks.index');
    }
}
