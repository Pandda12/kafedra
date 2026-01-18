<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreRequest;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        StoreRequest $request,
        TaskService $service,
        string $academicYearSlug
    ): RedirectResponse
    {
        $data = $request->validated();

        $service->store( $data, $academicYearSlug );

        return to_route('dashboard.task.index', $academicYearSlug);
    }
}
