<?php

namespace Javaabu\Paperless\FieldTypes\Types;

use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Helpers\Exceptions\InvalidOperationException;

abstract class FieldType
{
    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function getBuilder(): string
    {
        return $this->builder;
    }

    public function getBuilderInstance(FormField $form_field): IsComponentBuilder
    {
        return new ($this->getBuilder())($form_field);
    }

    public function getRenderParameters(): array
    {
        return [];
    }

    public static function make(string $slug)
    {
        $field_types = config('paperless.field_types');
        foreach($field_types as $field_type) {
            if ((new $field_type())->getSlug() === $slug) {
                return new $field_type();
            }
        }

        throw new InvalidOperationException("Field type not found: {$slug}");
    }

}
