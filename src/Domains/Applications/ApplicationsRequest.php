<?php

namespace Javaabu\Paperless\Domains\Applications;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Requests\BaseApplicationsRequest;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class ApplicationsRequest extends BaseApplicationsRequest
{
    public function rules(): array
    {
        $request_data = $this->all();

        $application_type_id = $this->input('application_type_id');
        $applicant_type_id = $this->input('applicant_type_id');

        $rules = [];
        $rules['application_type_id'] = ['exists:entity_type_application_type,application_type_id,entity_type_id,' . $applicant_type_id];
        $rules['applicant_type_id'] = ['exists:entity_type_application_type,entity_type_id,application_type_id,' . $application_type_id];
        $rules['applicant_id'] = ['exists:users,id'];

        $dynamic_field_rules = $this->getDynamicFieldRules($request_data);
        $rules = array_merge($rules, ...$dynamic_field_rules);


        if (! ($application = $this->route('application'))) {
            $rules['application_type_id'][] = 'required';
            $rules['applicant_type_id'][] = 'required';
            $rules['applicant_id'][] = 'required';
        }

        return $rules;
    }

    public function getApplicant(): Applicant
    {
        $applicant_id = $this->input('applicant_id');
        $applicant_type = $this->getApplicantType();
        return match($applicant_type) {
            'user' => config('paperless.models.user')::find($applicant_id),
            default => throw new InvalidOperationException('Invalid applicant')
        };
    }

    public function getApplicantType(): string
    {
        return 'user';
    }

    public function getApplicationType(): ?ApplicationType
    {
        $application_type_id = $this->input('application_type_id');
        return ApplicationType::find($application_type_id);
    }
}
