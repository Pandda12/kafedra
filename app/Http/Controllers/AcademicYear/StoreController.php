<?php

declare(strict_types=1);

namespace App\Http\Controllers\AcademicYear;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcademicYear\StoreRequest;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( StoreRequest $request ): RedirectResponse
    {
        $data = $request->validated();

        $data['settings'] = AcademicYear::DEFAULT_SETTINGS;

        AcademicYear::create($data);

        return redirect()->route('dashboard.academic_year.index');
    }
}
