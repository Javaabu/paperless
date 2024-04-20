<?php

namespace Javaabu\Paperless\Support;

use Javaabu\Helpers\Exceptions\InvalidOperationException;

abstract class ApplicationTypeCategory
{
    abstract public function getApplicationTypeCategorySlug(): string;
    abstract public function getApplicationTypeCategoryLabel(): string;

    public static function make(string $category_slug)
    {
        $application_type_categories = config('paperless.application_type_categories');
        foreach($application_type_categories as $application_type_category) {
            if ((new $application_type_category())->getApplicationTypeCategorySlug() === $category_slug) {
                return new $application_type_category();
            }
        }

        throw new InvalidOperationException("Application type category not found: {$category_slug}");
    }
}
