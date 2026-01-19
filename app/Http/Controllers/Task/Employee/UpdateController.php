<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Employee;

use App\Enums\{Roles, TaskStatus};
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\Employee\UpdateRequest;
use App\Notifications\TaskReviewed;
use App\Models\{File, Task, TaskUser, User};
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( UpdateRequest $request, Task $task ): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $userTask = TaskUser::where([
            'task_id' => $data['id'],
            'user_id' => $user->id,
        ])->firstOrFail();

        $updateData = [
            'status' => $data['status']
        ];

        if ( $data['status'] === TaskStatus::REVIEW->value ) {

            if ( $data['file'] ) {
                $file = $data['file'];
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $basename = pathinfo($originalName, PATHINFO_FILENAME);

                $filename = File::generateFilename('reports', $basename, $extension);
                $path = $file->storeAs('reports', $filename, 'local');

                $uploadFile = File::create([
                    'name' => $filename,
                    'disk' => 'public',
                    'path' => $path,
                    'extension' => $extension,
                    'size' => $file->getSize()
                ]);

                $updateData['report_file_id'] = $uploadFile->id;
            }

            $updateData['report_text'] = $data['description'];
            $updateData['reported_at'] = now();
        }

        $userTask->update($updateData);

        if ( $data['status'] === TaskStatus::REVIEW->value ) {
            $adminUsers = User::where('role', Roles::ADMIN->value)->get();
            Notification::send($adminUsers, new TaskReviewed($task, $userTask));
        }

        return to_route('employee.tasks.show', ['task' => $task]);
    }
}
