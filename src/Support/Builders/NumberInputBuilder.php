<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Support\Components\TextInput;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class NumberInputBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public static string $value = 'number_input';

    public function render(?string $input = null)
    {
        return TextInput::make($this->form_field->name)
                        ->markAsRequired($this->form_field->is_required)
                        ->type('number')
                        ->state($input)
                        ->toHtml();
    }
}
