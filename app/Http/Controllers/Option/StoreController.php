<?php

declare(strict_types=1);

namespace App\Http\Controllers\Option;

use App\Http\Controllers\Controller;
use App\Http\Requests\Option\StoreRequest;
use App\Models\AcademicYear;
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

        $academicYear->settings = $data;
        $academicYear->save();

        return to_route('dashboard.option.index', $academicYear->slug);
    }
}
