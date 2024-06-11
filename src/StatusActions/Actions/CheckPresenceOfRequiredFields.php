<?php

namespace Javaabu\Paperless\StatusActions\Actions;

use Javaabu\Paperless\Models\FormInput;
use Javaabu\Paperless\Domains\Applications\Application;

class CheckPresenceOfRequiredFields
{
    public function handle(Application $application): bool
    {
        $required_fields =
            $application->applicationType
                ->formFields()
                ->whereHas('formSection', function ($query) {
                    $query->where('is_admin_section', '!=', true);
                })
                ->where('is_required', true)
                ->get()
                ->pluck('id');

        $form_input_field_ids =
            $application->formInputs()
                        ->whereIn('form_field_id', $required_fields)
                        ->get()
                        ->filter(function (FormInput $form_input) {
                            return $form_input->isFilled();
                        })
                        ->pluck('form_field_id');

        return $required_fields->diff($form_input_field_ids)->isEmpty();
    }
}
