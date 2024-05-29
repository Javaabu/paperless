<?php

namespace Javaabu\Paperless\Requests;

use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Models\FieldGroup;
use Javaabu\Paperless\Models\FormSection;
use Illuminate\Foundation\Http\FormRequest;
use Javaabu\Paperless\Interfaces\Applicant;
use Illuminate\Database\Eloquent\Collection;
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
        // Get the application type and load its related form sections and fields if they are missing.
        $application_type = $this->getApplicationType();
        $application_type?->loadMissing('formSections', 'formSections.formFields');

        // Get the applicant type and applicant data.
        $applicant_type = $this->getApplicantType();
        $applicant = $this->getApplicant();

        // Initialize an empty array to hold validation rules.
        $rules = [];

        // If there is no application type, return the empty rules array.
        if (! $application_type) {
            return $rules;
        }

        // Load field groups and form fields for each form section of the application type.
        $application_type->formSections->load('fieldGroups', 'formFields');

        /**
         * Loop through each form section in the application type.
         * @var FormSection $section
         */
        foreach ($application_type->formSections as $section) {
            // Get the form fields that do not belong to any field group.
            $fields = $section->formFields->filter(fn ($field) => ! $field->field_group_id);

            /**
             * Loop through each field to get its validation rules and merge them into the rules array.
             * @var FormField $field
             */
            foreach ($fields as $field) {
                $field_rules = $field->validationRules($application_type, $applicant, $applicant_type, $request_data);
                $rules = array_merge($rules, $field_rules);
            }

            // Get the form fields that belong to field groups and group them by their field group ID.
            $grouped_fields = $section->formFields->filter(fn ($field) => $field->field_group_id)->groupBy('field_group_id');

            // Loop through each field group.
            foreach ($grouped_fields as $group_id => $group_fields) {
                /** @var FieldGroup $field_group */
                $field_group = FieldGroup::find($group_id);

                // Add a validation rule for the field group itself to be required and an array.
                $rules[$field_group->slug] = ['required', 'array'];

                // Get the values of the grouped fields from the request data.
                $group_field_values = data_get($request_data, $field_group->slug, []);

                /**
                 * Loop through each set of values for the grouped fields.
                 *
                 * @var int    $iteration
                 * @var  array $attribs
                 */
                foreach ($group_field_values as $iteration => $attribs) {
                    /**
                     * Get the fields for the current field group.
                     *
                     * @var Collection $field_group_in_question
                     */
                    $field_group_in_question = $grouped_fields->get($group_id);

                    /**
                     * Loop through each field in the field group to get its validation rules for the current iteration.
                     *
                     * @var FormField $field
                     */
                    foreach ($field_group_in_question as $field) {
                        $field_rules = $field->validationRules($application_type, $applicant, $applicant_type, $request_data, $iteration);

                        // Create a validation key for the field and add its rules to the rules array.
                        $validation_key = $field_group->slug . '.' . $iteration . '.' . $field->slug;
                        $rules[$validation_key] = $field_rules;
                    }
                }
            }
        }

        // Return the compiled array of validation rules.
        return $rules;
    }
}
