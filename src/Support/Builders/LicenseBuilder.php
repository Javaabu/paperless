<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use App\Models\FormInput;
use App\Models\Individual;
use App\Helpers\Enums\Genders;
use App\Helpers\Enums\Countries;
use App\DataObjects\IndividualData;
use Illuminate\Validation\Rules\Enum;
use App\Helpers\Enums\FormFieldTypes;
use App\Helpers\Enums\LicenseStatuses;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Models\Application;
use Javaabu\Paperless\Interfaces\Applicant;
use App\Exceptions\InvalidOperationException;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Support\Components\ApiSelect;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Paperless\Support\Components\IndividualSelect;
use App\Actions\Applications\FirstOrCreateIndividualAction;
use Illuminate\Contracts\Container\BindingResolutionException;

class LicenseBuilder extends ComponentBuilder implements IsComponentBuilder
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
            'license' => ['required', 'exists:licenses,id,status,' . LicenseStatuses::Issued->value . ',individual_id,' . $applicant->id]
        ];
    }
}
