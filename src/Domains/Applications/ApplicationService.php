<?php

namespace Javaabu\Paperless\Domains\Applications;

class ApplicationService
{
    public function createWithoutInputs($applicant, $applicationType)
    {
        $application_class = config('paperless.models.application');

        $application = new $application_class();
        $application->applicant()->associate($applicant);
        $application->applicationType()->associate($applicationType);
        $application->save();

        $status_enum = config('paperless.enums.application_status');
        $application->createStatusEvent(
            $status_enum::Draft->value,
            $status_enum::Draft->getRemarks()
        );

        return $application;
    }

    public function create($request)
    {
        $application_class = config('paperless.models.application');

        $application = new $application_class();
        $application->applicant()->associate($request->getApplicant());
        $application->applicationType()->associate($request->input('application_type_id'));
        $application->save();

        $input_data_array = collect($request->validated())->except([
            'applicant_type_id',
            'applicant_id',
            'application_type_id',
        ])->toArray();

        $application->updateFormInputs($input_data_array);

        $status_enum = config('paperless.enums.application_status');
        $application->createStatusEvent(
            $status_enum::Draft->value,
            $status_enum::Draft->getRemarks()
        );

        return $application;
    }

    public function update($request, $application)
    {
        $application->updateFormInputs($request->validated());
        return $application;
    }
}
