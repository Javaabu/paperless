<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

interface IsAnApplicationTypeService
{
    public static function getApplicationTypeClass(): string;
}
