<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\DatePicker;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class DatePickerBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public function render(?string $input = null)
    {
        return DatePicker::make($this->form_field->slug)
                         ->label($this->form_field->name)
                         ->markAsRequired($this->form_field->is_required)
                         ->state($input)
                         ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';
        return [
            $this->form_field->slug => [$is_required, 'date'],
        ];
    }
}
