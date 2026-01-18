<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYearUser\Dashboard;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\Dashboard\IndexResource;
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
        $employeeType = $request->input('type');
        $academicYear = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();

        if ( $employeeType ) {
            $users = $academicYear
                ->users()
                ->where('academic_year_user.role', $employeeType)
                ->orderBy('second_name')
                ->get();
        } else {
            $users = $academicYear
                ->users()
                ->orderBy('second_name')
                ->get();
        }

        $academicYearUsers = $users->map(fn ($user) =>
        (new IndexResource($user))
            ->withActiveYear($academicYear)
            ->resolve()
        )->values();

        return Inertia::render('Dashboard/AcademicYearUser/Index', [
            'year' => $academicYear,
            'import_roles' => Roles::getImportRoles(),
            'users' => $academicYearUsers
        ]);
    }
}
