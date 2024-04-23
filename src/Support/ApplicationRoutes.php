<?php

namespace Javaabu\Paperless\Support;

use Illuminate\Support\Facades\Route;
use Javaabu\Paperless\Models\FormSection;
use Javaabu\Paperless\Domains\Services\ServicesController;
use Javaabu\Paperless\Domains\DocumentTypes\DocumentTypesController;
use Javaabu\Paperless\Domains\Applications\ApplicationViewsController;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationTypesController;
use Javaabu\Paperless\Domains\Services\ApplicationType\ApplicationTypeServicesController;
use Javaabu\Paperless\Domains\FormSections\ApplicationType\ApplicationTypeFormFieldsController;
use Javaabu\Paperless\Domains\DocumentTypes\ApplicationType\ApplicationTypeDocumentTypesController;
use Javaabu\Paperless\Domains\ApplicationTypes\Api\ApplicationTypesController as ApiApplicationTypesController;

class ApplicationRoutes
{
    public static function apiRoutes(): void
    {
        Route::resource('application-types', ApiApplicationTypesController::class)->only(['index', 'show']);
    }

    public static function routes(): void
    {
        self::applicationCreateAndUpdateRoutes();
        self::applicationViewRoutes();
        self::serviceRoutes();
        self::applicationTypeRoutes();
        self::documentTypeRoutes();
        self::applicationTypeServices();
        self::applicationTypeDocuments();
        self::applicationTypeFields();
    }

    public static function applicationCreateAndUpdateRoutes(): void
    {
        $applications_controller = config('paperless.controllers.applications');

        //        Route::get('/template/field-groups/{field_group:slug}/download', [TemplateController::class, 'download'])->name('template.download');
        Route::match(['PUT', 'PATCH'], 'applications', [$applications_controller, 'bulk'])->name('applications.bulk');
        Route::match(['PUT', 'PATCH'], 'applications/{application}/status-update', [$applications_controller, 'statusUpdate'])->name('applications.status-update');
        Route::match(['PUT', 'PATCH'], 'applications/{application}/sections/{admin_section}/update', [$applications_controller, 'adminSectionUpdate'])->name('applications.admin-section-update');
        Route::match(['PUT', 'PATCH'], 'applications/{application}/assign-staff', [$applications_controller, 'assignStaff'])->name('applications.assign-staff');
        Route::get('applications/{application}/receipt', [$applications_controller, 'receipt'])->name('applications.receipt');
        Route::get('applications/{application}/review', [$applications_controller, 'review'])->name('applications.review');
        Route::get('applications/{application}/documents', [$applications_controller, 'documents'])->name('applications.documents');
        Route::get('applications/trash', [$applications_controller, 'trash'])->name('applications.trash');
        Route::post('applications/{id}/restore', [$applications_controller, 'restore'])->name('applications.restore');
        Route::delete('applications/{id}/force-delete', [$applications_controller, 'forceDelete'])->name('applications.force-delete');
        Route::resource('applications', $applications_controller)->except(['show']);
        Route::bind('admin_section', function ($value, $route) {
            $application_id = $route->parameter('application');

            return FormSection::where('id', $value)->whereHasApplication($application_id)->firstOrFail();
        });
    }

    public static function applicationViewRoutes(): void
    {
        /* Application View */
        Route::get('applications/{application}', [ApplicationViewsController::class, 'show'])->name('applications.show');
        Route::get('applications/{application}/details', [ApplicationViewsController::class, 'details'])->name('applications.details');
        Route::get('applications/{application}/view-documents', [ApplicationViewsController::class, 'viewDocuments'])->name('applications.view-documents');
        Route::get('applications/{application}/history', [ApplicationViewsController::class, 'history'])->name('applications.history');
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

    public static function applicationTypeFields(): void
    {
        Route::get('application-types/{application_type}/form-fields', [
            ApplicationTypeFormFieldsController::class, 'index',
        ])->name('application-types.form-fields.index');
    }
}
