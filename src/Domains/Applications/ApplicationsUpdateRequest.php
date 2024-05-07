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

        return array_merge($rules, $dynamic_field_rules);
    }

    public function getApplicant(): Applicant
    {
        $application = $this->route(config('paperless.routing.admin_application_param'))
            ?: $this->route(config('paperless.routing.public_application_param'));

        return $application->applicant;
    }

    public function getApplicantType(): string
    {
        $application = $this->route(config('paperless.routing.admin_application_param'))
            ?: $this->route(config('paperless.routing.public_application_param'));

        return $application->applicant_type;
    }

    public function getApplicationType(): ?ApplicationType
    {
        $application = $this->route(config('paperless.routing.admin_application_param'))
            ?: $this->route(config('paperless.routing.public_application_param'));

        return $application->applicationType;
    }
}
