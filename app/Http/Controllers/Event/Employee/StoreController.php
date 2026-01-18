<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\Employee\StoreRequest;
use App\Models\{AcademicYear, Event};
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( StoreRequest $request ): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $activeAcademicYear = AcademicYear::getActiveYear();

        $data['academic_year_id'] = $activeAcademicYear->id;
        $data['user_id'] = $user->id;

        Event::create($data);

        return to_route('employee.events.index');
    }
}
