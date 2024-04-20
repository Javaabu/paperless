<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes\Categories;

interface ApplicationTypeCategoryInterface
{
    public function getSlug(): string;
    public function getLabel(): string;
}
