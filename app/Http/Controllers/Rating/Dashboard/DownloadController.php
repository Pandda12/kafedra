<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rating\Dashboard;

use App\Actions\GetDashboardRatingChartDataAction;
use App\DTO\DashboardRatingChartParams;
use App\Exports\RatingExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DownloadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        string $academicYearSlug,
        GetDashboardRatingChartDataAction $action
    )
    {
        $params = new DashboardRatingChartParams(
            assignedType: (string) $request->get('assigned_type'),
            assignedAt: (array) $request->get('assigned_at', []),
            month: (int) ($request->get('month') ?? now()->month),
            academicYearSlug: $academicYearSlug
        );

        $data = $action->execute($params);
        $excel = $action->prepareToExport($data);

        $export = new RatingExport([$excel]);

        return Excel::download($export, 'ratings.xlsx');
    }
}
