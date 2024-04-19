<?php

namespace Javaabu\Paperless\Domains\EntityTypes;

interface EntityTypeEnumInterface
{
    public static function labels(): array;

    public static function getModelClassFromEntityTypeId(int $entity_type_id): string;
}
