<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share( Request $request ): array
    {
        $user = $request->user();

        if ( $request->route('academicYear') ) {
            $current = AcademicYear::where('slug', $request->route('academicYear'))->first();
        } else {
            $current = null;
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'second_name' => $user->second_name,
                    'email' => $user->email,
                    'role' => [
                        'value' => $user->role->value,
                        'label' => $user->role->label()
                    ]
                ] : null,
            ],
            'active_year' => AcademicYear::getActiveYear(),
            'current_year' => $current ? [
                'id' => $current->id,
                'name' => $current->name,
                'slug' => $current->slug
            ] : null,
//            'availableAcademicYears' => AcademicYear::query()
//                ->orderByDesc('slug')->get(['id','slug','name']),
        ];
    }
}
