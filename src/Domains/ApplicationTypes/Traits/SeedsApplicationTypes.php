<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes\Traits;

use Javaabu\Paperless\Domains\DocumentTypes\DocumentType;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;
use Javaabu\Paperless\Domains\ApplicationTypes\CreateFormFieldsAction;

trait SeedsApplicationTypes
{
    public function seed(): void
    {
        $application_type = $this->seedApplicationType();

        $application_type->documentTypes()->detach();
        $this->seedRequiredDocuments($application_type);
        $this->seedOptionalDocuments($application_type);

        $this->seedServices($application_type);

        $this->seedEntityTypes($application_type);

        $this->seedFormFields($application_type);
    }

    public function seedFormFields(ApplicationType $application_type): void
    {
        $form_fields = $this->getRequiredFormFields();
        if ($form_fields) {
            (new CreateFormFieldsAction())->handle($application_type, $form_fields);
        }
    }

    public function seedEntityTypes(ApplicationType $application_type): void
    {
        $entity_types = $this->getEntityTypes();
        $entity_type_model = config('paperless.models.entity_type');
        $entity_types = $entity_type_model::whereIn('slug', $entity_types)->get();
        $application_type->entityTypes()->sync($entity_types);
    }

    public function seedServices(ApplicationType $application_type): void
    {
        $automatically_applied_services = $this->getAutomaticallyAppliedServices();
        $manually_applied_services = $this->getManuallyAppliedServices();

        $service_model = config('paperless.models.service');
        $services = $service_model::whereIn('code', array_merge(
            $automatically_applied_services,
            $manually_applied_services
        ))->get();

        $services_array = [];

        foreach ($automatically_applied_services as $service_code) {
            $service_id = $services->where('code', $service_code)->first()?->id;
            $services_array[$service_id] = ['is_applied_automatically' => true];
        }

        foreach ($manually_applied_services as $service_code) {
            $service_id = $services->where('code', $service_code)->first()?->id;
            $services_array[$service_id] = ['is_applied_automatically' => false];
        }

        $application_type->services()->sync($services_array);
    }

    public function seedRequiredDocuments(ApplicationType $application_type): void
    {
        foreach ($this->getRequiredDocumentTypes() as $document_type_slug) {
            $document_type = DocumentType::where('slug', $document_type_slug)->first();
            $application_type->documentTypes()->attach($document_type, ['is_required' => true]);
        }
    }

    public function seedOptionalDocuments(ApplicationType $application_type): void
    {
        foreach ($this->getOptionalDocumentTypes() as $document_type_slug) {
            $document_type = DocumentType::where('slug', $document_type_slug)->first();
            $application_type->documentTypes()->attach($document_type, ['is_required' => false]);
        }
    }

    public function seedApplicationType(): ApplicationType
    {
        $eta_duration = property_exists($this, 'eta_duration') ? $this->eta_duration : 5;

        $application_category = (new ($this->getCategory()))->getSlug();

        $application_type_class = config('paperless.models.application_type');

        return $application_type_class::updateOrCreate([
            'code' => $this->code,
        ], [
            'name'                 => $this->getName(),
            'description'          => $this->getDescription(),
            'eta_duration'         => $eta_duration,
            'application_category' => $application_category,
        ]);
    }
}