<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes\Categories;

use Javaabu\Helpers\Exceptions\InvalidOperationException;

abstract class ApplicationTypeCategory
{
    abstract public function getSlug(): string;
    abstract public function getLabel(): string;

    public static function make(string $category_slug)
    {
        $application_type_categories = static::getAllApplicationCategories();
        foreach($application_type_categories as $application_type_category) {
            if ((new $application_type_category())->getApplicationTypeCategorySlug() === $category_slug) {
                return new $application_type_category();
            }
        }

        throw new InvalidOperationException("Application type category not found: {$category_slug}");
    }

    public static function getAllApplicationCategories()
    {
        return config('paperless.application_type_categories');
    }

    public static function getAllApplicationCategoriesArray(): array
    {
        $application_type_categories = static::getAllApplicationCategories();
        $categories = [];
        foreach($application_type_categories as $application_type_category) {
            $category = new $application_type_category();
            $categories[$category->getSlug()] = $category->getLabel();
        }

        return $categories;
    }

}
