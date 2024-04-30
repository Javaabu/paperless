<?php

namespace Javaabu\Paperless\Domains\Applications;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Requests\BaseApplicationsRequest;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class ApplicationsUpdateRequest extends BaseApplicationsRequest
{
    public function rules(): array
    {
        $request_data = $this->all();
        $rules = [];
        $dynamic_field_rules = $this->getDynamicFieldRules($request_data);

        return array_merge($rules, ...$dynamic_field_rules);
    }

    public function getApplicant(): Applicant
    {
        $application = $this->route('application') ?: $this->route('public_application');

        return $application->applicant;
    }

    public function getApplicantType(): string
    {
        $application = $this->route('application') ?: $this->route('public_application');

        return $application->applicant_type;
    }

    public function getApplicationType(): ?ApplicationType
    {
        $application = $this->route('application') ?: $this->route('public_application');

        return $application->applicationType;
    }
}
