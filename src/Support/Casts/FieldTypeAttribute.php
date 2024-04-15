<?php

namespace Javaabu\Paperless\Support\Casts;

use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\FieldTypes\Types\FieldType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FieldTypeAttribute implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return FieldType::make($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof FieldType) {
            return $value->getSlug();
        }

        return $value;
    }
}
