<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYear;

use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Event, Publication, Task};
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class ShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $AcademicYearSlug ): Response
    {
        $year = AcademicYear::where('slug', $AcademicYearSlug)->firstOrFail();

        $tasks = Task::where('academic_year_id', $year->id)->getCountForPagination();
        $publications = Publication::where('academic_year_id', $year->id)->getCountForPagination();
        $events = Event::where('academic_year_id', $year->id)->getCountForPagination();

        return Inertia::render('Dashboard/AcademicYear/Show', [
            'year' => $year,
            'task_count' => $tasks,
            'publication_count' => $publications,
            'event_count' => $events
        ]);
    }
}
