<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use Javaabu\Paperless\Support\Components\TextInput;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class NumberInputBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public string $value = 'text_input';

    public function render(?string $input = null)
    {
        return TextInput::make($this->form_field->name)
                        ->markAsRequired($this->form_field->is_required)
                        ->type('number')
                        ->state($input)
                        ->toHtml();
    }
}
