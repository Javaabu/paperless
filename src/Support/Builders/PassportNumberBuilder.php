<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use App\Models\Individual;
use App\Helpers\Enums\FormFieldTypes;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\TextInput;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class PassportNumberBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public string $value = 'text_input';

    public function render(?string $input = null)
    {
        return TextInput::make($this->form_field->slug)
                        ->label($this->form_field->name)
                        ->markAsRequired($this->form_field->is_required)
                        ->state($input)
                        ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';
        $name = data_get($request_data, 'full_name');
        $name_dv = data_get($request_data, 'full_name_dv');
        $nationality = data_get($request_data, 'nationality');
        $gender = data_get($request_data, 'gender');
        $date_of_birth = data_get($request_data, 'date_of_birth');

        return [
            $this->form_field->slug => [
                $is_required,
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($applicant, $name, $name_dv, $nationality, $gender, $date_of_birth) {
                    $individual = Individual::where('gov_id', $value)->first();
                    if ($individual && $individual->name != $name) {
                        $fail(__('The entered passport number and name does not match with the existing record.'));
                    }
                    if ($individual && $individual->name_dv != $name_dv) {
                        $fail(__('The entered passport number and name does not match with the existing record.'));
                    }
                    if ($individual && $individual->nationality_id != $nationality) {
                        $fail(__('The entered passport number and nationality does not match with the existing record.'));
                    }
                    if ($individual && $individual->gender != $gender) {
                        $fail(__('The entered passport number and gender does not match with the existing record.'));
                    }
                    if ($individual && $individual->dob && $individual->dob != $date_of_birth) {
                        $fail(__('The entered passport number and date of birth does not match with the existing record.'));
                    }
                },
            ],
        ];
    }
}
