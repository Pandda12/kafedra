<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\UpdateRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        UpdateRequest $request,
        TaskService $service,
        string $academicYearSlug,
        Task $task
    ): RedirectResponse
    {
        $data = $request->validated();

        $service->update( $task, $data, $academicYearSlug );

        return to_route('dashboard.task.edit', [$academicYearSlug, $task]);
    }
}
