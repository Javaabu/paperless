<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Domains\DocumentTypes\DocumentType;
use Javaabu\Paperless\Support\Actions\GetClassesInDirectory;

abstract class ApplicationTypeService implements IsAnApplicationTypeService
{
    private static array $serviceMapping = [];

    /**
     * @throws \ReflectionException
     */
    public static function make(ApplicationType $application_type)
    {
        $service_classes = (new GetClassesInDirectory())->handle(config('paperless.services.path'), config('paperless.services.namespace'));
        foreach ($service_classes as $service_class) {
            /** @var ApplicationTypeBlueprint $application_type_class */
            $application_type_class = $service_class::getApplicationTypeClass();

            if ((new $application_type_class())->getCode() === $application_type->code) {
                return new $service_class();
            }
        }

        return null;
    }

    public static function getMorphClass(): string
    {
        $application_type_class = static::$application_type_class ?? null;
        dd($application_type_class);

        return (new $application_type_class())->getCode();
    }

    public static function getServiceMapping(): Collection
    {
        if (! isset(self::$serviceMapping[static::class])) {
            self::$serviceMapping[static::class] = static::resolveServiceMapping();
        }

        return collect(self::$serviceMapping[static::class]);
    }


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

    public static function getServiceClassMapping()
    {
        $service_classes = (new GetClassesInDirectory())->handle(config('paperless.application_types.services_path'));

    }
}
