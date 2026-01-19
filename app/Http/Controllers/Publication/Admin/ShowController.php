<?php

declare(strict_types=1);

namespace App\Http\Controllers\Publication\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Publication\Dashboard\ShowResource;
use App\Models\{AcademicYear, Publication};
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class ShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $academicYearSlug, Publication $publication ): Response
    {
        return Inertia::render('Dashboard/Publication/Show', [
            'year' => AcademicYear::where('slug', $academicYearSlug)->firstOrFail(),
            'publication' => ShowResource::make($publication)->resolve()
        ]);
    }
}
