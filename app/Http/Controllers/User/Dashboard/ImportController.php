<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Dashboard;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\AcademicYear;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $academicYearSlug ): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'string', Rule::in([
                Roles::FULL_TIME_EMPLOYEE->value,
                Roles::PART_TIME_EMPLOYEE->value
            ])],
            'file' => ['required', 'file', 'mimes:xlsx']
        ]);

        $academicYear = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();

        Excel::import(new UsersImport( $academicYear->id, $data['role'] ), $data['file']);

        return to_route('dashboard.academic_year_user.index', $academicYearSlug);
    }
}
