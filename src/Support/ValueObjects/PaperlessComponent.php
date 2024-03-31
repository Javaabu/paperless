<?php

namespace Javaabu\Paperless\Support\ValueObjects;

use Javaabu\Paperless\Support\Builders\TextInputBuilder;

class PaperlessComponent
{
    public static function types(): array
    {
        return [
            'text_input' => TextInputBuilder::class,
        ];
    }

}
