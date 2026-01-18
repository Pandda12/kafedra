<?php

declare(strict_types=1);

namespace App\Http\Controllers\Publication\Admin;

use App\Enums\UserSelectType;
use App\Http\Controllers\Controller;
use App\Models\{AcademicYear, Publication, User};
use App\Http\Resources\Publication\Dashboard\IndexResource;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $activeYear = AcademicYear::getActiveYear();
        $publications = Publication::where('academic_year_id', $activeYear->id)->get();

        return Inertia::render('Dashboard/Publication/Index', [
            'year' => $activeYear,
            'users' => User::getAllEmployees(),
            'user_type_select' => UserSelectType::getSelectOptions(),
            'publications' => IndexResource::collection($publications)->resolve()
        ]);
    }
}
