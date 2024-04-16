<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Models\FormInput;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Interfaces\Applicant;

abstract class ComponentBuilder
{
    public function __construct(
        public FormField $form_field,
    ) {
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';
        return [
            $this->form_field->slug => [$is_required],
        ];
    }

    public function saveInputs(Application $application, FormField $form_field, array | null $form_inputs = []): void
    {
        $form_input_value = $form_inputs[$form_field->slug] ?? null;
        $array_value = in_array($form_field->type->getSlug(), ['select']);

        $form_input = $application->formInputs()->where('form_field_id', $form_field->id)->first();
        if (! $form_input) {
            $form_input = new FormInput();
            $form_input->application()->associate($application);
            $form_input->formField()->associate($form_field);
        }

        $form_input->value = $array_value ? json_encode($form_input_value) : $form_input_value;
        $form_input->save();
    }
}
