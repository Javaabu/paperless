<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Textarea;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class TextareaBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public string $value = 'textarea';

    public function render(?string $input = null)
    {
        return Textarea::make($this->form_field->slug)
                       ->label($this->form_field->name)
                       ->markAsRequired($this->form_field->is_required)
                       ->state($input)
                       ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';
        return [
            $this->form_field->slug => [$is_required, 'string', 'max:65535'],
        ];
    }
}
