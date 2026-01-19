<?php

declare(strict_types=1);

namespace App\Http\Controllers\Option;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
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

        return Inertia::render('Dashboard/Option/Index', [
            'year' => $academicYear,
            'options' => $academicYear->settings
        ]);
    }
}
