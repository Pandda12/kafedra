<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Dashboard;

use App\Enums\UserSelectType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Task\Dashboard\EditResource;
use App\Models\{AcademicYear, Task, User};
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $academicYearSlug, Task $task ): Response
    {
        $year = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();

        return Inertia::render('Dashboard/Task/Edit', [
            'task' => EditResource::make($task)->resolve(),
            'year' => ['name' => $year->name, 'slug' => $year->slug],
            'user_type_select' => UserSelectType::getSelectOptions(),
            'users' => User::getAllEmployees()
        ]);
    }
}
