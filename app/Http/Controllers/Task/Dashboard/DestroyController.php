<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\{RedirectResponse, Request};

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $academicYearSlug, Task $task ): RedirectResponse
    {
        $task->delete();

        return to_route('dashboard.task.index', $academicYearSlug);
    }
}
