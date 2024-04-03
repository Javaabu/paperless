<?php

namespace Javaabu\Paperless\FieldTypes\Types;

use Javaabu\Paperless\Support\Builders\TextInputBuilder;

class EmailInput extends FieldType
{
    protected string $slug = 'email_input';
    protected string $label = 'Email Input';
    protected string $icon = 'text-size';
    protected string $builder = TextInputbuilder::class;

}
