<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Dashboard;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class ReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $academicYearSlug ): Response
    {
        $academicYear = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();

        $users = $academicYear
            ->users()
            ->orderBy('second_name')
            ->get()
            ->map(fn ($user) => [
                'value' => $user->id,
                'label' => $user->getFullName(),
            ]);

        return Inertia::render('Dashboard/Task/Report', [
            'year' => $academicYear,
            'users' => $users,
            'task_statuses' => TaskStatus::getReportStatuses()
        ]);
    }
}
