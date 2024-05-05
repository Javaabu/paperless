<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Support\Components\TextInput;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Paperless\Support\Components\RepeatingGroup;

class NumberInputBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public static string $value = 'number_input';

    public function render(?string $input = null)
    {
        return TextInput::make($this->form_field->name)
                        ->repeatingGroup(function () {
                            if ($this->form_field->field_group_id) {
                                return RepeatingGroup::make($this->form_field->fieldGroup->name)
                                                     ->id($this->form_field->fieldGroup->slug);
                            }

                            return null;
                        })
                        ->markAsRequired($this->form_field->is_required)
                        ->type('number')
                        ->state($input)
                        ->toHtml();
    }
}
