<?php

namespace Javaabu\Paperless\FieldTypes\Types;

use Javaabu\Paperless\Support\Builders\TextInputBuilder;

class TextInput extends FieldType
{
    protected string $slug = 'text_input';
    protected string $label = 'Text Input';
    protected string $icon = 'text-size';
}
