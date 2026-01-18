<?php

declare(strict_types=1);

namespace App\Http\Controllers\Publication\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publication\Employee\StoreRequest;
use App\Models\{AcademicYear, File, Publication};
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( StoreRequest $request ): RedirectResponse
    {
        $data = $request->validated();

        $activeAcademicYear = AcademicYear::getActiveYear();

        $data['academic_year_id'] = $activeAcademicYear->id ?? null;
        $data['user_id'] = $request->user()->id;

        if ( $data['file'] ) {
            try {
                $file = $data['file'];
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $basename = pathinfo($originalName, PATHINFO_FILENAME);

                $filename = File::generateFilename('publications', $basename, $extension);
                $path = $file->storeAs('publications', $filename, 'local');

                $uploadFile = File::create([
                    'name' => $filename,
                    'disk' => 'public',
                    'path' => $path,
                    'extension' => $extension,
                    'size' => $file->getSize()
                ]);

                $data['file_id'] = $uploadFile->id;
            } catch ( \Exception $e ) {
                Log::info($e->getMessage());
            }
        }

        Publication::create($data);

        return to_route('employee.publications.index');
    }
}
