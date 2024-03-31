<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\License;
use App\Models\FormField;
use App\Models\Individual;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class TonnageCategoryBuilder extends ComponentBuilder implements IsComponentBuilder
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
        return [
            'tonnage' => [
                'required',
                'exists:tonnage_categories,id',
                function ($attribute, $value, $fail) use ($applicant) {
                    /* @var Individual $applicant */
                    if ($applicant->tonnageEndorsements?->contains('tonnage_category_id', $value)) {
                       $fail(__("The selected tonnage category is already assigned to the given applicant"));
                    }
                },
            ],
        ];
    }
}
