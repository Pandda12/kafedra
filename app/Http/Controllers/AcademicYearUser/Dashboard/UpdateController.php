<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYearUser\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicYearUser\Dashboard\UpdateRequest;
use App\Models\AcademicYearUser;
use Illuminate\Http\RedirectResponse;

class UpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        UpdateRequest $request,
        string $academicYearSlug,
        AcademicYearUser $academicYearUser
    ): RedirectResponse
    {
        $data = $request->validated();

        $academicYearUser->update([
            'user_id' => $data['user'],
            'role' => $data['role']
        ]);

        return to_route('dashboard.academic_year_user.edit', [
            'academicYear' => $academicYearSlug,
            'academic_year_user' => $academicYearUser
        ]);
    }
}
