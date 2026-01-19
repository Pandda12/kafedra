<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event\Employee;

use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Event};
use App\Http\Resources\Event\Employee\IndexResource;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $activeYear = AcademicYear::getActiveYear();

        $events = Event::where([
            'academic_year_id' => $activeYear->id,
            'user_id' => $request->user()->id
        ])->get();

        return Inertia::render('Employee/Event/Index', [
            'events' => IndexResource::collection( $events )->resolve(),
            'event_rating' => $activeYear->settings['event_rating'] ?? 0
        ]);
    }
}
