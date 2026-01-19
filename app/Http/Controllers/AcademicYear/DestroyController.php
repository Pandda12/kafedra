<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYear;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\{RedirectResponse, Request};

class DestroyController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, AcademicYear $academicYear ): RedirectResponse
    {
        $academicYear->delete();

        return to_route('dashboard.academic_year.index');
    }
}
