<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Employee/Task/Create');
    }
}
