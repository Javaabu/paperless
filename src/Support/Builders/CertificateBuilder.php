<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class CertificateBuilder extends ComponentBuilder implements IsComponentBuilder
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

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        return [
            'certificate' => ['nullable', 'exists:certificates,id,individual_id,' . $applicant->id]
        ];
    }
}
