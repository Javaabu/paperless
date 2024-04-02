<?php

namespace Javaabu\Paperless\FieldTypes\Types;

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

    public function getRenderParameters(): array
    {
        return [];
    }

}
