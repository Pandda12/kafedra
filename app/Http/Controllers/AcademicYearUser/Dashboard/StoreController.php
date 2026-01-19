<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYearUser\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicYearUser\Dashboard\StoreRequest;
use App\Models\{AcademicYear, AcademicYearUser};
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( StoreRequest $request, string $academicYearSlug ): RedirectResponse
    {
        $data = $request->validated();
        $academicYear = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();

        AcademicYearUser::create([
            'academic_year_id' => $academicYear->id,
            'user_id' => $data['user'],
            'role' => $data['role']
        ]);

        return to_route('dashboard.academic_year_user.index', $academicYearSlug);
    }
}
