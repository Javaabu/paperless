<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Paperless\Support\Components\EmailInput;
use Javaabu\Paperless\Support\Components\RepeatingGroup;

class EmailInputBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public static string $value = 'email_input';

    public function render(?string $input = null, int | null $instance = null)
    {
        return EmailInput::make($this->form_field->slug)
                        ->repeatingGroup(function () {
                            if ($this->form_field->field_group_id) {
                                return RepeatingGroup::make($this->form_field->fieldGroup->name)
                                                     ->id($this->form_field->fieldGroup->slug);
                            }

                            return null;
                        })
                        ->conditionalOn(
                            $this->form_field->meta['conditional_on'],
                            $this->form_field->meta['conditional_value'],
                            checkbox: $this->form_field->meta['conditional_checkbox']
                        )
                        ->repeatingInstance($instance)
                        ->label($this->form_field->name)
                        ->dhivehi($this->form_field->language->isDhivehi())
                        ->markAsRequired($this->form_field->is_required)
                        ->state($input)
                        ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = [], ?int $iteration = null): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';

        return [
            $this->form_field->slug => [$is_required, 'string', 'max:255'],
        ];
    }
}
