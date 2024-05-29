<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Javaabu\Paperless\Domains\ApplicationTypes\Traits\SeedsApplicationTypes;

abstract class ApplicationTypeBlueprint implements IsAnApplicationType
{
    use SeedsApplicationTypes;

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
}
