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
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Models\Application;
use Javaabu\Paperless\Interfaces\Applicant;
use App\Exceptions\InvalidOperationException;
use App\Rules\IsValidIndividualGovernmentIdRule;
use Javaabu\Paperless\Support\Components\ApiSelect;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Paperless\Support\Components\RepeatingGroup;
use Javaabu\Paperless\Support\Components\IndividualSelect;
use App\Actions\Applications\FirstOrCreateIndividualAction;
use Illuminate\Contracts\Container\BindingResolutionException;

class IndividualSelectBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public function render(Model | string | array | null $input = null, ?string $api_url = '', ?array $filter_by = [], int|null $instance = null)
    {
        return IndividualSelect::make($this->form_field->slug)
                               ->repeatingGroup(function () {
                                   if ($this->form_field->field_group_id) {
                                       return RepeatingGroup::make($this->form_field->fieldGroup->name)
                                                            ->id($this->form_field->fieldGroup->slug);
                                   }

                                   return null;
                               })
                               ->repeatingInstance($instance)
                               ->label($this->form_field->name)
                               ->markAsRequired($this->form_field->is_required)
                               ->state($input)
                               ->toHtml();
    }


    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $maldives_id = Countries::getMaldivesId();
        $request_nationality_id = data_get($request_data, 'nationality');

        $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255', 'exists:countries,id'],
            'gov_id'      => [
                'required',
                'string',
                'max:255',
                new IsValidIndividualGovernmentIdRule($request_nationality_id, $request_data),
            ],
            'name_dv'     => ['nullable', "required_unless:nationality,{$maldives_id}", 'string', 'max:255'],
            'gender'      => ['nullable', "required_unless:nationality,{$maldives_id}", new Enum(Genders::class), 'string', 'max:255'],
        ];

        if ($maldives_id == $request_nationality_id) {
            $rules['gov_id'][] = 'regex:/^A[0-9]{6}$/';
        }

        return $rules;
    }

    /**
     * @throws BindingResolutionException
     */
    public function saveInputs(Application $application, FormField $form_field, array|null $form_inputs = []): void
    {
        $name = data_get($form_inputs, 'name');
        $nationality = data_get($form_inputs, 'nationality');
        $gov_id = data_get($form_inputs, 'gov_id');
        $name_dv = data_get($form_inputs, 'name_dv');
        $gender = data_get($form_inputs, 'gender');
        $maldives_id = Countries::getMaldivesId();

        // check if there is an existing individual with the same id card number
        $individual = Individual::where('gov_id', $gov_id)->first();

        // exists: check if the name matches, if name doesnt match, fail
        if ($nationality == $maldives_id) { // If maldivian
            if ($individual && $individual->name != $name) {
                throw new InvalidOperationException('The name does not match the ID card number');
            }

            // doesnt exist: fetch the details from DNR
            if (! $individual) {

                // fetch details from DNR
                $individual_information = dnr()->getCitizenByIdAndName($gov_id, $name);
                if (! $individual_information->isSuccessful()) {
                    throw new InvalidOperationException($individual_information->getErrorMessage());
                }

                $individual_data = IndividualData::fromCitizenData($individual_information->toDto());
                $individual = (new FirstOrCreateIndividualAction)->handle($individual_data);
            }
        } else { // if foreigner
            $individual_data = IndividualData::fromArray([
                'name'    => $name,
                'name_dv' => $name_dv,
                'gov_id'  => $gov_id,
                'gender'  => $gender,
            ]);

            $individual = (new FirstOrCreateIndividualAction)->handle($individual_data);
        }


        $form_input_value = $individual?->id;
        $form_input = $application->formInputs()->where('form_field_id', $form_field->id)->first();

        if (! $form_input) {
            $form_input = new FormInput();
            $form_input->application()->associate($application);
            $form_input->formField()->associate($form_field);
        }

        $form_input->value = $form_input_value;
        $form_input->save();
    }
}
