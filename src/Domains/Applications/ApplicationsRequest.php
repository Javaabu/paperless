<?php

namespace Javaabu\Paperless\Domains\Applications;

use Illuminate\Validation\Rule;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Domains\EntityTypes\EntityType;
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
        $rules['application_type_id'] = [
            Rule::exists('entity_type_application_type', 'application_type_id')
                ->where('entity_type_id', $applicant_type_id),
        ];
        $rules['applicant_type_id'] = [
            Rule::exists('entity_type_application_type', 'entity_type_id')
                ->where('application_type_id', $application_type_id),
        ];
        $rules['applicant_id'] = [
            Rule::exists(config('paperless.public_user_table'), 'id'),
        ];

        $dynamic_field_rules = $this->getDynamicFieldRules($request_data);
        $rules = array_merge($rules, ...$dynamic_field_rules);

        if (! ($application = $this->route(config('paperless.routing.admin_application_param')))) {
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

        $map = [];
        foreach (config('paperless.entity_type_enum')::cases() as $entity_type) {
            $map[$entity_type->value] = $entity_type->modelClass()::find($applicant_id);
        }

        if (array_key_exists($applicant_type, $map)) {
            return $map[$applicant_type];
        } else {
            throw new InvalidOperationException('Invalid applicant');
        }
    }

    public function getApplicantType(): string
    {
        $applicant_type_id = $this->input('applicant_type_id');

        return EntityType::query()
                         ->where('id', $applicant_type_id)
                         ->firstOrFail()
                         ->slug;
    }

    public function getApplicationType(): ?ApplicationType
    {
        $application_type_id = $this->input('application_type_id');

        return ApplicationType::find($application_type_id);
    }
}
