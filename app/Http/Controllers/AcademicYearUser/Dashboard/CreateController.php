<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYearUser\Dashboard;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, User};
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request, string $academicYearSlug ): Response
    {
        $academicYear = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();

        $assigneeUsersIds = $academicYear->users->pluck('id')->toArray();
        $usersToAssignee = User::query()
            ->where('role', Roles::EMPLOYEE->value)
            ->whereNotIn('id', $assigneeUsersIds)
            ->get()
            ->map(fn ($user) => [
                'value' => $user->id,
                'label' => $user->getFullName()
            ]);

        return Inertia::render('Dashboard/AcademicYearUser/Create', [
            'year' => $academicYear,
            'users' => $usersToAssignee,
            'roles' => Roles::getAssigneeRoles()
        ]);
    }
}
