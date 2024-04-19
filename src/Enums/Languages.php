<?php

namespace Javaabu\Paperless\Enums;

enum Languages: string implements IsEnum
{
    use NativeEnumsTrait;

    case English = 'en';
    case Dhivehi = 'dv';

    public static function labels(): array
    {
        return [
            self::English->value => __("English"),
            self::Dhivehi->value => __("Dhivehi"),
        ];
    }

    public function isDhivehi(): bool
    {
        return $this->value === self::Dhivehi->value;
    }

}
