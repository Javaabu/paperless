<?php

namespace Javaabu\Paperless\Support;

use Illuminate\Support\Facades\Route;
use Javaabu\Paperless\Models\FormSection;

class ApplicationRoutes
{


    public static function serviceRoutes()
    {
        Route::match(['PUT', 'PATCH'], 'services', [ServicesController::class, 'bulk'])->name('services.bulk');
        Route::get('services/trash', [ServicesController::class, 'trash'])->name('services.trash');
        Route::post('services/{id}/restore', [ServicesController::class, 'restore'])->name('services.restore');
        Route::delete('services/{id}/force-delete', [ServicesController::class, 'forceDelete'])->name('services.force-delete');
        Route::resource('services', ServicesController::class);
    }


    public static function routes(): void
    {
        self::serviceRoutes();
//        Route::get('/template/field-groups/{field_group:slug}/download', [TemplateController::class, 'download'])->name('template.download');
//        Route::match(['PUT', 'PATCH'], 'applications', [ApplicationsController::class, 'bulk'])->name('applications.bulk');
//        Route::match(['PUT', 'PATCH'], 'applications/{application}/status-update', [ApplicationsController::class, 'statusUpdate'])->name('applications.status-update');
//        Route::match(['PUT', 'PATCH'], 'applications/{application}/sections/{admin_section}/update', [ApplicationsController::class, 'adminSectionUpdate'])->name('applications.admin-section-update');
//        Route::match(['PUT', 'PATCH'], 'applications/{application}/assign-staff', [ApplicationsController::class, 'assignStaff'])->name('applications.assign-staff');
//        Route::get('applications/{application}/receipt', [ApplicationsController::class, 'receipt'])->name('applications.receipt');
//        Route::get('applications/{application}/review', [ApplicationsController::class, 'review'])->name('applications.review');
//        Route::get('applications/{application}/documents', [ApplicationsController::class, 'documents'])->name('applications.documents');
//        Route::get('applications/trash', [ApplicationsController::class, 'trash'])->name('applications.trash');
//        Route::post('applications/{id}/restore', [ApplicationsController::class, 'restore'])->name('applications.restore');
//        Route::delete('applications/{id}/force-delete', [ApplicationsController::class, 'forceDelete'])->name('applications.force-delete');
//        Route::resource('applications', ApplicationsController::class)->except(['show']);
//
//        Route::bind('admin_section', function ($value, $route) {
//            $application_id = $route->parameter('application');
//            return FormSection::where('id', $value)->whereHasApplication($application_id)->firstOrFail();
//        });
    }
}
