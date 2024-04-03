<?php

namespace Javaabu\Paperless\Requests;

use App\Models\ApplicationType;
use Illuminate\Foundation\Http\FormRequest;
use Javaabu\Paperless\Interfaces\Applicant;
use App\Actions\GetCertificateValidationRulesAction;

abstract class BaseApplicationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    abstract public function getApplicantType(): string;

    abstract public function getApplicant(): Applicant;

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
                $rules[] = $field->validationRules($application_type, $applicant, $applicant_type, $request_data);
            }

            foreach ($grouped_fields as $group_id => $fields) {
                $group = $section->fieldGroups->firstWhere('id', $group_id);

                if ($group->slug == 'certificates') {
                    $rules[] = (new GetCertificateValidationRulesAction())->handle($request_data);
                }
            }
        }

        return $rules;
    }

    public function getApplicationType(): ?ApplicationType
    {
        $application_type_id = $this->input('application_type_id');
        return \App\Models\ApplicationType::find($application_type_id);
    }
}
