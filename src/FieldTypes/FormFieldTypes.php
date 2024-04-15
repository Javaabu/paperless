<?php

namespace Javaabu\Paperless\FieldTypes;

use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Helpers\Exceptions\InvalidOperationException;

class FormFieldTypes
{
    public static function getBuilder(FormField $form_field): IsComponentBuilder
    {
        $field_types = config('paperless.field_types');
        foreach($field_types as $field_type) {
            if ((new $field_type())->getSlug() === $form_field->type) {
                return new $field_type($form_field);
            }
        }

        throw new InvalidOperationException("Builder not found for form field type: {$form_field->type->value}");
    }
}
