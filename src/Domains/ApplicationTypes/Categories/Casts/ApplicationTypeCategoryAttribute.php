<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes\Categories\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Javaabu\Paperless\Domains\ApplicationTypes\Categories\ApplicationTypeCategory;

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
            return $value->getSlug();
        }

        return $value;
    }
}
