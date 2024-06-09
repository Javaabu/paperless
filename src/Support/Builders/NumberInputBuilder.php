<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Paperless\Support\Components\NumberInput;
use Javaabu\Paperless\Support\Components\RepeatingGroup;

class NumberInputBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public static string $value = 'number_input';

    public function getRenderParameters($field, $application, $entity, ?int $instance = null): array
    {
        return [
            $instance,
        ];
    }

    public function render(?string $input = null, ?int $instance = null)
    {
        return NumberInput::make($this->form_field->slug)
                          ->repeatingGroup(function () {
                              if ($this->form_field->field_group_id) {
                                  return RepeatingGroup::make($this->form_field->fieldGroup->name)
                                                       ->id($this->form_field->fieldGroup->slug);
                              }

                              return null;
                          })
                          ->repeatingInstance($instance)
                          ->markAsRequired($this->form_field->is_required)
                          ->state($input)
                          ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = [], ?int $iteration = null): array
    {
        return [
            'required',
            'integer',
            'min:1',
            'max:999999999',
        ];
    }
}