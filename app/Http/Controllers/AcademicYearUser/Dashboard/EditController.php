<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYearUser\Dashboard;

use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Resources\AcademicYearUser\Dashboard\EditResource;
use App\Models\{AcademicYear, AcademicYearUser, User};
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class EditController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        string $academicYearSlug,
        AcademicYearUser $academicYearUser
    ): Response
    {
        $academicYear = AcademicYear::where('slug', $academicYearSlug)->firstOrFail();

        $assigneeUsersIds = $academicYear->users()
            ->whereNot('users.id', $academicYearUser->user_id)
            ->pluck('users.id')
            ->toArray();

        $usersToAssignee = User::query()
            ->where('role', Roles::EMPLOYEE->value)
            ->whereNotIn('id', $assigneeUsersIds)
            ->get()
            ->map(fn ($user) => [
                'value' => $user->id,
                'label' => $user->getFullName()
            ]);

        return Inertia::render('Dashboard/AcademicYearUser/Edit', [
            'user' => EditResource::make($academicYearUser)->resolve(),
            'year' => $academicYear,
            'users' => $usersToAssignee,
            'roles' => Roles::getAssigneeRoles()
        ]);
    }
}
