<?php

namespace Javaabu\Paperless\Enums;

interface IsEnum
{
    public static function labels(): array;

    public function getLabel(): string;
}
