<?php

namespace Javaabu\Paperless\Requests;

use Javaabu\Paperless\Models\FieldGroup;
use Illuminate\Foundation\Http\FormRequest;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

abstract class BaseApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    abstract public function getApplicantType(): string;

    abstract public function getApplicant(): Applicant;
    abstract public function getApplicationType(): ?ApplicationType;

    public function getDynamicFieldRules(?array $request_data = []): array
    {
        $application_type = $this->getApplicationType();
        $application_type?->loadMissing('formSections', 'formSections.formFields');
        $applicant_type = $this->getApplicantType();
        $applicant = $this->getApplicant();
        $rules = [];

        if (! $application_type) {
            return $rules;
        }


        $application_type->formSections->load('fieldGroups', 'formFields');

        foreach ($application_type->formSections as $section) {
            $fields = $section->formFields->filter(fn ($field) => ! $field->field_group_id);

            $grouped_fields = $section->formFields->filter(fn ($field) => $field->field_group_id)->groupBy('field_group_id');

            foreach ($fields as $field) {
                $field_rules = $field->validationRules($application_type, $applicant, $applicant_type, $request_data);
                $rules = array_merge($rules, $field_rules);
            }

            foreach ($grouped_fields as $group_id => $group_fields) {
                $field_group = FieldGroup::find($group_id);
                $rules[$field_group->slug] = ['required', 'array'];

                foreach ($group_fields as $field) {
                    $field_rules = $field->validationRules($application_type, $applicant, $applicant_type, $request_data);

                    $validation_key = $field_group->slug . '.*.' . $field->slug;

                    $rules[$validation_key] = $field_rules;
                }
            }
        }

        return $rules;
    }
}
