<?php

namespace Javaabu\Paperless\Support\Casts;

use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\FieldTypes\Types\FieldType;
use Javaabu\Paperless\Support\Builders\ComponentBuilder;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FieldBuilderAttribute implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        /* @var FormField $model */
        return ComponentBuilder::make($value, $model);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof ComponentBuilder) {
            return $value->getSlug();
        }

        return $value;
    }
}
