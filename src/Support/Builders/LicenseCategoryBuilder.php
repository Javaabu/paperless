<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\License;
use App\Models\FormField;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class LicenseCategoryBuilder extends ComponentBuilder implements IsComponentBuilder
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
        $license = data_get($request_data, 'license');
        return [
            'license_category' => [
                $is_required,
                'exists:license_categories,id',
                function ($attribute, $value, $fail) use ($applicant, $license) {
                    if ($license) { // Excludes new license creation
                        // The given license should not have the selected category
                        $license_has_category = License::where('id', $license)
                                                       ->whereHas('licenseCategories', function ($query) use ($value) {
                                                           $query->where('license_category_id', $value);
                                                       })
                                                       ->exists();
                        if ($license_has_category) {
                            $fail(__("The selected category is already assigned to the given license"));
                        }
                    }
                },
            ],
        ];
    }
}
