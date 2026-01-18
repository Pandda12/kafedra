<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYear;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicYear\UpdateRequest;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( UpdateRequest $request, string $slug ): RedirectResponse
    {
        $data = $request->validated();

        $academicYear = AcademicYear::where('slug', $slug)->firstOrFail();
        $academicYear->update($data);

        return to_route('dashboard.academic_year.index');
    }
}
