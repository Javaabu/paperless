<?php

namespace Javaabu\Paperless\Support\Casts;

use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\FieldTypes\Types\FieldType;
use Javaabu\Paperless\Support\ApplicationTypeCategory;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ApplicationTypeCategoryAttribute implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return ApplicationTypeCategory::make($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value instanceof ApplicationTypeCategory) {
            return $value->getApplicationTypeCategorySlug();
        }

        return $value;
    }
}
