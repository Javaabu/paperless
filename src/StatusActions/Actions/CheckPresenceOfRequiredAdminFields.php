<?php

namespace Javaabu\Paperless\StatusActions\Actions;


use Javaabu\Paperless\Domains\Applications\Application;

class CheckPresenceOfRequiredAdminFields
{
    public function handle(Application $application): bool
    {
        $required_fields = $application->applicationType->formFields()
                                                        ->whereHas('formSection', function ($query) {
                                                            $query->where('is_admin_section', true);
                                                        })
                                                        ->where('is_required', true)
                                                        ->get()
                                                        ->pluck('id');

        $form_input_field_ids = $application->formInputs->whereIn('form_field_id', $required_fields)
                                                        ->whereNotNull('value')
                                                        ->pluck('form_field_id');

        return $required_fields->diff($form_input_field_ids)->isEmpty();
    }
}
