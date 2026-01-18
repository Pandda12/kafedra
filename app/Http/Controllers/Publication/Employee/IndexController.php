<?php

declare(strict_types=1);

namespace App\Http\Controllers\Publication\Employee;

use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Publication};
use App\Http\Resources\Publication\Employee\IndexResource;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $activeYear = AcademicYear::getActiveYear();
        $publications = Publication::where([
            'academic_year_id' => $activeYear->id,
            'user_id' => $request->user()->id
        ])->get();

        return Inertia::render('Employee/Publication/Index', [
            'publications' => IndexResource::collection( $publications )->resolve(),
            'publication_rating' => $activeYear->settings['publication_rating'] ?? 0
        ]);
    }
}
