<?php

namespace Javaabu\Paperless\Domains\EntityTypes;

use Illuminate\Support\Facades\Cache;

trait ActsAsEntityTypeEnum
{
    public function getEntityType(): EntityType
    {
        $key_name = $this->value . "_entity_type";
        return Cache::get($key_name, function () {
            return EntityType::where('slug', $this->value)->first();
        });
    }

    public static function getEntityTypeFromId(int $type_id): ?EntityType
    {
        // cache the entity type
        return Cache::remember(
            "entity_type_{$type_id}",
            now()->addDay(),
            function () use ($type_id) {
                return EntityType::find($type_id);
            }
        );
    }

    public function getEntityTypeId()
    {
        return $this->getEntityType()->id;
    }

    public function getEntityTypeSlug()
    {
        return $this->getEntityType()->slug;
    }
}
