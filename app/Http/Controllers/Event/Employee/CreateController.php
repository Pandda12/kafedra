<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event\Employee;

use App\Http\Controllers\Controller;
use App\Models\ParticipationForm;
use Illuminate\Http\Request;
use Inertia\{Inertia, Response};

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke( Request $request ): Response
    {
        $participationForms = ParticipationForm::query()
            ->where('is_active', 1)
            ->get()
            ->map(fn ($type) => [
                'value' => $type->id,
                'label' => $type->name
            ]);

        return Inertia::render('Employee/Event/Create', [
            'participation_forms' => $participationForms
        ]);
    }
}
