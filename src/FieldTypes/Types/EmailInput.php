<?php

namespace Javaabu\Paperless\FieldTypes\Types;

use Javaabu\Paperless\Support\Builders\EmailInputBuilder;

class EmailInput extends FieldType
{
    protected string $slug = 'email_input';
    protected string $label = 'Email Input';
    protected string $icon = 'text-size';
}
