<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\TextInput;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Paperless\Support\Components\RepeatingGroup;

class EmailInputBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public string $value = 'text_input';

    public function render(?string $input = null, int|null $instance = null)
    {
        return TextInput::make($this->form_field->slug)
                        ->repeatingGroup(function () {
                            if ($this->form_field->field_group_id) {
                                return RepeatingGroup::make($this->form_field->fieldGroup->name)
                                                     ->id($this->form_field->fieldGroup->slug);
                            }

                            return null;
                        })
                        ->repeatingInstance($instance)
                        ->label($this->form_field->name)
                        ->dhivehi($this->form_field->language->isDhivehi())
                        ->markAsRequired($this->form_field->is_required)
                        ->state($input)
                        ->type('email')
                        ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';
        return [
            $this->form_field->slug => [$is_required, 'string', 'max:255'],
        ];
    }
}
