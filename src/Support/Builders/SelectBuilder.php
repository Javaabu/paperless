<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class SelectBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public function render(?string $input = null, ?array $options = [], ?bool $multiple = false)
    {
        return Select::make($this->form_field->slug)
                     ->label($this->form_field->name)
                     ->markAsRequired($this->form_field->is_required)
                     ->multiple($multiple)
                     ->options($options)
                     ->state($input)
                     ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = [], ?int $iteration = null): array
    {
        $field_options = $this->form_field->type?->getSelectOptions($this->form_field) ?? [];
        $allowed_values = array_keys($field_options);

        $rules = [
            $this->form_field->slug => [
                $this->form_field->is_required ? 'required' : 'nullable',
            ],
        ];

        if (! empty($allowed_values)) {
            $rules[$this->form_field->slug][] = "in:" . implode(',', $allowed_values);
        }

        return $rules;
    }
}
