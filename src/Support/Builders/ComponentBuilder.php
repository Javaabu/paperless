<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Models\FormInput;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\Support\InfoLists\Components\TextEntry;

abstract class ComponentBuilder
{
    public function __construct(
        public FormField $form_field,
    ) {
    }

    public function getRenderParameters($field, $entity, int|null $instance = null): array
    {
        return [];
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';

        return [
            $this->form_field->slug => [$is_required],
        ];
    }

    public function getValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $rules = $this->getDefaultValidationRules($applicant, $request_data);

        if ($this->form_field->field_group_id) {
            return $rules;
        }

        return [
            $this->form_field->slug => $rules,
        ];
    }

    public function saveInputs(Application $application, FormField $form_field, array | null $form_inputs = []): void
    {
        $form_input_value = $form_inputs[$form_field->slug] ?? null;
        $array_value = static::getValue() == 'select';

        $form_input = $application->formInputs()->where('form_field_id', $form_field->id)->first();
        if (! $form_input) {
            $form_input = new FormInput();
            $form_input->application()->associate($application);
            $form_input->formField()->associate($form_field);
        }

        $form_input->value = $array_value ? json_encode($form_input_value) : $form_input_value;
        $form_input->save();
    }

    public function renderInfoList(FormField $form_field, $value = null): string
    {
        return TextEntry::make($form_field->name)
                        ->markAsRequired($form_field->is_required)
                        ->value($value)
                        ->toHtml();
    }

    public static function getValue(): string
    {
        return static::$value;
    }

    public function getSlug(): string
    {
        return static::$value;
    }

    public static function make(string $builder, FormField $form_field)
    {
        $field_types = config('paperless.field_builders');
        foreach($field_types as $field_type) {
            if ($field_type::getValue() === $builder) {
                return new $field_type($form_field);
            }
        }

        throw new InvalidOperationException("Field type not found: {$builder}");
    }
}
