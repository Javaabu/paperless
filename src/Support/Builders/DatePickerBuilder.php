<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Paperless\Support\Components\DatePicker;
use Javaabu\Paperless\Support\Components\RepeatingGroup;

class DatePickerBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public static string $value = 'datepicker';

    public function render(?string $input = null)
    {
        return DatePicker::make($this->form_field->slug)
                         ->label($this->form_field->name)
                         ->repeatingGroup(function () {
                             if ($this->form_field->field_group_id) {
                                 return RepeatingGroup::make($this->form_field->fieldGroup->name)
                                                      ->id($this->form_field->fieldGroup->slug);
                             }

                             return null;
                         })
                         ->markAsRequired($this->form_field->is_required)
                         ->state($input)
                         ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = [], ?int $iteration = null): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';

        return [$is_required, 'date'];
    }
}
