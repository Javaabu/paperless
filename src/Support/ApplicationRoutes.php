<?php

namespace Javaabu\Paperless\Support;

use Illuminate\Support\Facades\Route;
use Javaabu\Paperless\Domains\Services\ServicesController;
use Javaabu\Paperless\Domains\DocumentTypes\DocumentTypesController;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationTypesController;
use Javaabu\Paperless\Domains\Services\ApplicationType\ApplicationTypeServicesController;

class ApplicationRoutes
{
    public static function routes(): void
    {
        self::serviceRoutes();
        self::applicationTypeRoutes();
        self::documentTypeRoutes();
        self::applicationTypeServices();
        self::applicationTypeDocuments();
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


    public static function documentTypeRoutes(): void
    {
        Route::match(['PUT', 'PATCH'], 'document-types', [DocumentTypesController::class, 'bulk'])->name('document-types.bulk');
        Route::get('document-types/trash', [DocumentTypesController::class, 'trash'])->name('document-types.trash');
        Route::post('document-types/{id}/restore', [DocumentTypesController::class, 'restore'])->name('document-types.restore');
        Route::delete('document-types/{id}/force-delete', [DocumentTypesController::class, 'forceDelete'])->name('document-types.force-delete');
        Route::resource('document-types', DocumentTypesController::class);
    }

    public static function applicationTypeServices(): void
    {
        Route::match(['PUT', 'PATCH'], 'application-types/{application_type}/services/bulk', [ApplicationTypeServicesController::class, 'bulk'])->name('application-types.services.bulk');
        Route::resource('application-types.services', ApplicationTypeServicesController::class)
             ->scoped()
             ->except(['create', 'edit', 'update']);
    }

    public static function applicationTypeDocuments(): void
    {
        Route::match(['PUT', 'PATCH'], 'application-types/{application_type}/document-types/bulk', [ApplicationTypeDocumentTypesController::class, 'bulk'])->name('application-types.document-types.bulk');
        Route::resource('application-types.document-types', ApplicationTypeDocumentTypesController::class)
             ->scoped()
             ->except(['create', 'edit', 'update']);
    }
}
