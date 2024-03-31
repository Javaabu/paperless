<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use App\Models\Institution;
use App\Helpers\Enums\ExaminerStatuses;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class InstitutionBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public function render(?string $input = null, ?array $options = [], ?bool $multiple = false)
    {
        if ($multiple) {
            $input = json_decode($input);
        }

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
            'institution' => [
                $is_required,
                function ($attribute, $value, $fail) use ($applicant) {
                    $institution = Institution::where('id', $value)
                                              ->where('is_available_for_exam', true)
                                              ->whereRelation('examiners', 'status', ExaminerStatuses::Active->value)
                                              ->exists();

                    if (!$institution) {
                        $fail('The selected institution is not available for exam');
                    }
                },
            ],
        ];
    }
}
