<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event\Dashboard;

use App\Enums\UserSelectType;
use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Event, User};
use App\Http\Resources\Event\Dashboard\IndexResource;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $academicYearSlug ): Response
    {
        $academicYear = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();
        $events = Event::where('academic_year_id', $academicYear->id)->get();

        return Inertia::render('Dashboard/Event/Index', [
            'year' => $academicYear,
            'users' => User::getAllEmployees(),
            'user_type_select' => UserSelectType::getSelectOptions(),
            'events' => IndexResource::collection($events)->resolve()
        ]);
    }
}
