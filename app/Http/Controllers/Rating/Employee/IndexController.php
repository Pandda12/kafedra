<?php

declare(strict_types=1);

namespace App\Http\Controllers\Rating\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $months = [
            [
                'value' => 1,
                'label' => 'Январь'
            ],
            [
                'value' => 2,
                'label' => 'Февраль'
            ],
            [
                'value' => 3,
                'label' => 'Март'
            ],
            [
                'value' => 4,
                'label' => 'Апрель'
            ],
            [
                'value' => 5,
                'label' => 'Май'
            ],
            [
                'value' => 6,
                'label' => 'Июнь'
            ],
            [
                'value' => 7,
                'label' => 'Июль'
            ],
            [
                'value' => 8,
                'label' => 'Август'
            ],
            [
                'value' => 9,
                'label' => 'Сентябрь'
            ],
            [
                'value' => 10,
                'label' => 'Октябрь'
            ],
            [
                'value' => 11,
                'label' => 'Ноябрь'
            ],
            [
                'value' => 12,
                'label' => 'Декабрь'
            ]
        ];

        return Inertia::render('Employee/Rating/Index', [
            'months' => $months
        ]);
    }
}
