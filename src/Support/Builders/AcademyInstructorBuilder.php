<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class AcademyInstructorBuilder extends ComponentBuilder implements IsComponentBuilder
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
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';
        return [
            'academy_instructor' => [
                $is_required,
                'exists:academy_instructors,instructor_id,academy_id,' . $applicant->id . ',status,active',
            ]
        ];
    }
}
