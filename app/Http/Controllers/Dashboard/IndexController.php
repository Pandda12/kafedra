<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Publication, Task, User};
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $academicYearsCount = AcademicYear::all()->count();
        $usersCount = User::all()->count();
        $tasksCount = Task::all()->count();
        $publicationsCount = Publication::all()->count();
        $eventsCount = Task::all()->count();

        return Inertia::render('Dashboard/Index', [
            'academic_years' => $academicYearsCount,
            'users' => $usersCount,
            'tasks' => $tasksCount,
            'publications' => $publicationsCount,
            'events' => $eventsCount
        ]);
    }
}
