<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Dashboard;

use App\Enums\UserSelectType;
use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Task, User};
use App\Http\Resources\Task\Dashboard\IndexResource;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $academicYearSlug ): Response
    {
        $year = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();
        $tasks = Task::where('academic_year_id', $year->id)->get();

        return Inertia::render('Dashboard/Task/Index', [
            'year' => $year,
            'tasks' => IndexResource::collection( $tasks )->resolve(),
            'user_type_select' => UserSelectType::getSelectOptions(),
            'users' => User::getAllEmployees()
        ]);
    }
}
