<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYear;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $slug ): Response
    {
        $academicYear = AcademicYear::where('slug', $slug)->firstOrFail();

        return Inertia::render('Dashboard/AcademicYear/Edit', [
            'year' => $academicYear
        ]);
    }
}
