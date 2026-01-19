<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\Task\Employee\ShowResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class ShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, Task $task ): Response
    {
        return Inertia::render('Employee/Task/Show', [
            'task' => ShowResource::make($task)->resolve()
        ]);
    }
}
