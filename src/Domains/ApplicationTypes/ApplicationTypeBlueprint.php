<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Notifications\Traits\SendsApplicationNotifications;
use Javaabu\Paperless\Domains\ApplicationTypes\Traits\SeedsApplicationTypes;

abstract class ApplicationTypeBlueprint implements IsAnApplicationType
{
    use SeedsApplicationTypes;
    use SendsApplicationNotifications;

    public int $eta_duration = 5;

    public function getDescription(): string
    {
        return "";
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getSlug(): string
    {
        return str($this->getName())->slug('_');
    }

    public function getRequiredDocumentTypes(): array
    {
        return [];
    }

    public function getOptionalDocumentTypes(): array
    {
        return [];
    }

    public function getAutomaticallyAppliedServices(): array
    {
        return [];
    }

    public function getManuallyAppliedServices(): array
    {
        return [];
    }

    public function getServiceClass(): ?string
    {
        if (property_exists($this, 'service')) {
            return $this->service;
        }

        return null;
    }

    public function getExtraProcessActions(string $current_status, Application $application): array
    {
        return [];
    }

    public function getExtraBladeViewsToRender(string $page_name): array
    {
        return match ($page_name) {
            'summary' => $this->extraBladeViewsToRenderForSummary(),
            'details' => $this->extraBladeViewsToRenderForDetails(),
            'history' => $this->extraBladeViewsToRenderForHistory(),
        };
    }

    public function extraBladeViewsToRenderForSummary(): array
    {
        return [];
    }

    public function extraBladeViewsToRenderForDetails(): array
    {
        return [];
    }

    public function extraBladeViewsToRenderForHistory(): array
    {
        return [];
    }

    public function isStatusBeforeApprove($application): bool
    {
        return true;
    }

    public function canStart($entity): bool
    {
        if ($entity instanceof Model) {
            $entity = $entity->getMorphClass();
        }

        return in_array($entity, $this->getEntityTypes());
    }
}
