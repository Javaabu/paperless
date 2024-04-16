<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Models\DocumentType;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

abstract class ApplicationTypeService
{
    public function doBeforeSubmitting(Application $application): void
    {
    }

    public function doAfterSubmitting(Application $application): void
    {
    }

    public function associateDocuments(Application $application, HasMedia $generated_model, array $document_types = []): void
    {
        $documents = $application->getMedia('documents');

        foreach ($document_types as $document_type) {
            $document_type_id = DocumentType::where('slug', $document_type)->first()?->id;
            if (! $document_type_id) {
                continue;
            }

            /* @var Media $document */
            $document = $documents->where('document_type_id', $document_type_id)->first();
            $document?->copy($generated_model, $document_type);
        }
    }

    public function associateAdditionalDocuments(Application $application, HasMedia $generated_model, string $collection_name = 'documents'): void
    {
        $documents = $application->getMedia('documents')->whereNull('document_type_id');

        foreach ($documents as $document) {
            /* @var Media $document */
            $document?->copy($generated_model, $collection_name);
        }
    }
}
