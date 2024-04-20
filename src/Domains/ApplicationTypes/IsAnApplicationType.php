<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

interface IsAnApplicationType
{
    public function getName(): string;
    public function getRequiredFormFields(): array;
    public function getCategory(): string;
    public function getEntityTypes(): array;
}
