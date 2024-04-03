<?php

namespace Javaabu\Paperless\Support;

use Illuminate\Support\Facades\Route;
use Javaabu\Paperless\Domains\Services\ServicesController;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationTypesController;

class ApplicationRoutes
{
    public static function routes(): void
    {
        self::serviceRoutes();
        self::applicationTypeRoutes();
    }

    public static function serviceRoutes(): void
    {
        Route::match(['PUT', 'PATCH'], 'services', [ServicesController::class, 'bulk'])->name('services.bulk');
        Route::get('services/trash', [ServicesController::class, 'trash'])->name('services.trash');
        Route::post('services/{id}/restore', [ServicesController::class, 'restore'])->name('services.restore');
        Route::delete('services/{id}/force-delete', [ServicesController::class, 'forceDelete'])->name('services.force-delete');
        Route::resource('services', ServicesController::class);
    }

    public static function applicationTypeRoutes(): void
    {
        Route::match(['PUT', 'PATCH'], 'application-types', [ApplicationTypesController::class, 'bulk'])->name('application-types.bulk');
        Route::post('application-types/{application_type}/upload', [ApplicationTypesController::class, 'upload'])->name('application-types.upload');
        Route::resource('application-types', ApplicationTypesController::class)->except(['create', 'store', 'destroy']);
    }
}
