<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Notification\ApiController as NotificationApiController;
use App\Http\Controllers\Task\Dashboard\ApiController as TaskApiController;
use App\Http\Middleware\{AdminMiddleware, EmployeeMiddleware};
use App\Http\Controllers\User\Dashboard\UpdateController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require_once __DIR__ . '/auth.php';

/*
 * Admin routes
 */
Route::group([
    'prefix' => 'dashboard',
    'middleware' => AdminMiddleware::class,
], function () {

    Route::get('/', \App\Http\Controllers\Dashboard\IndexController::class)
        ->name('dashboard');

    Route::get('/notifications', \App\Http\Controllers\Notification\Admin\IndexController::class)
        ->name('dashboard.notification.index');

    Route::group(['prefix' => 'year'], function () {

        Route::get('/', \App\Http\Controllers\AcademicYear\IndexController::class)
            ->name('dashboard.academic_year.index');

        Route::get('/create', \App\Http\Controllers\AcademicYear\CreateController::class)
            ->name('dashboard.academic_year.create');

        Route::post('/', \App\Http\Controllers\AcademicYear\StoreController::class)
            ->name('dashboard.academic_year.store');

        Route::get('/{academicYear}/edit', \App\Http\Controllers\AcademicYear\EditController::class)
            ->name('dashboard.academic_year.edit');

        Route::patch('/{academicYear}', \App\Http\Controllers\AcademicYear\UpdateController::class)
            ->name('dashboard.academic_year.update');

        Route::delete('/{academicYear}', \App\Http\Controllers\AcademicYear\DestroyController::class)
            ->name('dashboard.academic_year.destroy');
    });

    Route::group(['prefix' => 'users'], function () {

        Route::get('/', \App\Http\Controllers\User\Dashboard\IndexController::class)
            ->name('dashboard.user.index');

        Route::get('/create', \App\Http\Controllers\User\Dashboard\CreateController::class)
            ->name('dashboard.user.create');

        Route::post('/', \App\Http\Controllers\User\Dashboard\StoreController::class)
            ->name('dashboard.user.store');

        Route::get('/{user}/edit', \App\Http\Controllers\User\Dashboard\EditController::class)
            ->name('dashboard.user.edit');

        Route::patch('/{user}', [UpdateController::class, 'profileUpdate'])
            ->name('dashboard.user.profile_update');

        Route::put('/{user}', [UpdateController::class, 'passwordUpdate'])
            ->name('dashboard.user.password_update');

        Route::delete('/{user}/', \App\Http\Controllers\User\Dashboard\DestroyController::class)
            ->name('dashboard.user.destroy');
    });


    Route::group(['prefix' => 'api'], function () {

        Route::get('/notifications', [NotificationApiController::class, 'getAdminNotifications'])
            ->name('dashboard.api.notification.index');

        Route::get('/download-file', [\App\Http\Controllers\File\ApiController::class, 'downloadFile'])
            ->name('dashboard.api.file.download');

        Route::patch('/notifications/{notification}/read', \App\Http\Controllers\Notification\UpdateController::class)
            ->name('dashboard.api.notification.update');

        Route::patch('/task/{task:id}', [TaskApiController::class, 'updateTaskStatus'])
            ->name('dashboard.api.task.update');
    });

    Route::group([
        'prefix' => '{academicYear:slug}'
    ], function () {

        Route::get('/', \App\Http\Controllers\AcademicYear\ShowController::class)
            ->name('dashboard.academic_year.show');

        Route::get('/reports', \App\Http\Controllers\Task\Dashboard\ReportController::class)
            ->name('dashboard.task.report');

        Route::group(['prefix' => 'tasks'], function () {

            Route::get('/', \App\Http\Controllers\Task\Dashboard\IndexController::class)
                ->name('dashboard.task.index');

            Route::get('/create', \App\Http\Controllers\Task\Dashboard\CreateController::class)
                ->name('dashboard.task.create');

            Route::post('/', \App\Http\Controllers\Task\Dashboard\StoreController::class)
                ->name('dashboard.task.store');

            Route::get('/{task:id}/edit', \App\Http\Controllers\Task\Dashboard\EditController::class)
                ->name('dashboard.task.edit');

            Route::patch('/{task:id}', \App\Http\Controllers\Task\Dashboard\UpdateController::class)
                ->name('dashboard.task.update');

            Route::delete('/{task}/', \App\Http\Controllers\Task\Dashboard\DestroyController::class)
                ->name('dashboard.task.destroy');

        });

        Route::group(['prefix' => 'publications'], function () {

            Route::get('/', \App\Http\Controllers\Publication\Admin\IndexController::class)
                ->name('dashboard.publication.index');

            Route::get('/{publication:id}', \App\Http\Controllers\Publication\Admin\ShowController::class)
                ->name('dashboard.publication.show');

        });

        Route::group(['prefix' => 'events'], function () {

            Route::get('/', \App\Http\Controllers\Event\Dashboard\IndexController::class)
                ->name('dashboard.event.index');

        });

        Route::group(['prefix' => 'ratings'], function () {

            Route::get('/', \App\Http\Controllers\Rating\Dashboard\IndexController::class)
                ->name('dashboard.rating.index');

            Route::get('/download', \App\Http\Controllers\Rating\Dashboard\DownloadController::class)
                ->name('dashboard.rating.download');

        });

        Route::group(['prefix' => 'users'], function () {

            Route::get('/', \App\Http\Controllers\AcademicYearUser\Dashboard\IndexController::class)
                ->name('dashboard.academic_year_user.index');

            Route::get('/add', \App\Http\Controllers\AcademicYearUser\Dashboard\CreateController::class)
                ->name('dashboard.academic_year_user.create');

            Route::post('/', \App\Http\Controllers\AcademicYearUser\Dashboard\StoreController::class)
                ->name('dashboard.academic_year_user.store');

            Route::post('/import', \App\Http\Controllers\User\Dashboard\ImportController::class)
                ->name('dashboard.user.import');

            Route::get('/{academic_year_user:id}/edit', \App\Http\Controllers\AcademicYearUser\Dashboard\EditController::class)
                ->name('dashboard.academic_year_user.edit');

            Route::patch('/{academic_year_user:id}', \App\Http\Controllers\AcademicYearUser\Dashboard\UpdateController::class)
                ->name('dashboard.academic_year_user.update');

            Route::delete('/{academic_year_user:id}/', \App\Http\Controllers\AcademicYearUser\Dashboard\DestroyController::class)
                ->name('dashboard.academic_year_user.destroy');

        });

        Route::group(['prefix' => 'settings'], function () {

            Route::get('/', \App\Http\Controllers\Option\IndexController::class)
                ->name('dashboard.option.index');

            Route::post('/', \App\Http\Controllers\Option\StoreController::class)
                ->name('dashboard.option.store');

        });

        Route::group(['prefix' => 'api'], function () {

            Route::get('/get-tasks-data', [TaskApiController::class, 'getDashboardTasksChartData'])
                ->name('dashboard.api.tasks.charts');

            Route::get('/get-publication-data', [TaskApiController::class, 'getDashboardPublicationsChartData'])
                ->name('dashboard.api.publications.charts');

            Route::get('/get-events-data', [TaskApiController::class, 'getDashboardEventsChartData'])
                ->name('dashboard.api.event.charts');

            Route::get('/get-reports', [TaskApiController::class, 'getUserReports'])
                ->name('dashboard.api.reports');

            Route::get('/get-rating-data', [TaskApiController::class, 'getDashboardRatingChartData'])
                ->name('dashboard.api.rating.charts');

        });

    });
});

/*
 * Employee routes
 */
Route::group([
    'middleware' => EmployeeMiddleware::class,
], function () {

    Route::get('/', \App\Http\Controllers\Home\Employee\IndexController::class)
        ->name('home');

    Route::group(['prefix' => 'tasks'], function () {

        Route::get('/', \App\Http\Controllers\Task\Employee\IndexController::class)
            ->name('employee.tasks.index');

        Route::get('/create', \App\Http\Controllers\Task\Employee\CreateController::class)
            ->name('employee.task.create');

        Route::post('/', \App\Http\Controllers\Task\Employee\StoreController::class)
            ->name('employee.task.store');

        Route::get('/{task:id}', \App\Http\Controllers\Task\Employee\ShowController::class)
            ->name('employee.tasks.show');

        Route::post('/{task:id}', \App\Http\Controllers\Task\Employee\UpdateController::class)
            ->name('employee.tasks.update');
    });

    Route::group(['prefix' => 'publications'], function () {

        Route::get('/', \App\Http\Controllers\Publication\Employee\IndexController::class)
            ->name('employee.publications.index');

        Route::get('/create', \App\Http\Controllers\Publication\Employee\CreateController::class)
            ->name('employee.publications.create');

        Route::post('/', \App\Http\Controllers\Publication\Employee\StoreController::class)
            ->name('employee.publications.store');

    });

    Route::group(['prefix' => 'events'], function () {

        Route::get('/', \App\Http\Controllers\Event\Employee\IndexController::class)
            ->name('employee.events.index');

        Route::get('/create', \App\Http\Controllers\Event\Employee\CreateController::class)
            ->name('employee.events.create');

        Route::post('/', \App\Http\Controllers\Event\Employee\StoreController::class)
            ->name('employee.events.store');

    });

    Route::get('/notifications', function () {
        return Inertia::render('Employee/Notification/Index');
    })->name('employee.notification.index');

    Route::get('/rating', \App\Http\Controllers\Rating\Employee\IndexController::class)
        ->name('employee.rating.index');

    Route::group(['prefix' => 'api'], function () {

        Route::get('/notifications', \App\Http\Controllers\Notification\IndexController::class)
            ->name('employee.api.notification.index');

        Route::patch('/notifications/{notification}/read', \App\Http\Controllers\Notification\UpdateController::class)
            ->name('employee.api.notification.update');

        Route::patch('/task/{task:id}', \App\Http\Controllers\Task\Employee\UpdateController::class)
            ->name('employee.api.task.update');

        Route::get('/get-data-charts', [TaskApiController::class, 'getEmployeeTasksChartData'])
            ->name('employee.api.tasks.charts');

        Route::get('/get-rating-data', [TaskApiController::class, 'getEmployeeRatingChartData'])
            ->name('employee.api.rating.charts');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
