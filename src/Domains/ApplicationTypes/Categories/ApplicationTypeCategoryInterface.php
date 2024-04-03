<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes\Categories;

interface ApplicationTypeCategoryInterface
{
    public function getApplicationTypeCategorySlug(): string;
    public function getApplicationTypeCategoryLabel(): string;
}
