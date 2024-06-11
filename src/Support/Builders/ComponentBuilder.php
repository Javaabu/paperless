<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Models\FormInput;
use Javaabu\Paperless\Models\FieldGroup;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\Support\InfoLists\Components\TextEntry;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;

abstract class ComponentBuilder
{
    public function __construct(
        public FormField $form_field,
    )
    {
    }

    public function getRenderParameters($field, $application, $entity, int | null $instance = null): array
    {
        return [];
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = [], ?int $iteration = null): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';

        return [
            $this->form_field->slug => [$is_required],
        ];
    }

    public function getValidationRules(Applicant $applicant, ?array $request_data = [], ?int $iteration): array
    {
        $rules = $this->getDefaultValidationRules($applicant, $request_data, $iteration);

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

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function saveFileForFormField(Application $application, FormField $form_field, array | null $input_files = []): void
    {
        if (! in_array($form_field->slug, array_keys($input_files))) {
            return;
        }

        /** @var FormInput $form_input */
        $form_input = $application->formInputs()->where('form_field_id', $form_field->id)->first();

        if ($form_input) {
            $form_input->value = 'attachment';
            $form_input->save();
            $form_input->addMediaFromRequest($form_field->slug)
                       ->toMediaCollection('attachment');
        }
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function saveFieldGroupFormFieldFile(
        Application $application,
        FormField   $form_field,
        FieldGroup  $field_group,
        int         $group_instance_number,
        ?array      $input_files = []
    ): void
    {
        /** @var FormInput $form_input */
        $form_input = $application->formInputs()
                                  ->where('form_field_id', $form_field->id)
                                  ->where('field_group_id', $field_group->id)
                                  ->where('group_instance_number', $group_instance_number)
                                  ->first();

        $file_key = $input_files[$field_group->slug][$group_instance_number][$form_field->slug] ?? null;

        if ($form_input && $file_key) {
            $form_input->value = 'attachment';
            $form_input->save();

            $form_input->addMedia($file_key)
                       ->withCustomProperties([
                           'instance' => $group_instance_number,
                       ])
                       ->toMediaCollection('attachment');
        }
    }


    public function saveFieldGroupInputs(Application $application, FormField $form_field, FieldGroup $field_group, int $group_instance_number, ?array $form_inputs = []): void
    {
        $form_input_value = $form_inputs[$form_field->slug] ?? null;
        $array_value = static::getValue() == 'select';

        $form_input = $application->formInputs()
                                  ->where('form_field_id', $form_field->id)
                                  ->where('field_group_id', $field_group->id)
                                  ->where('group_instance_number', $group_instance_number)
                                  ->first();

        if (! $form_input) {
            $form_input = new FormInput();
            $form_input->application()->associate($application);
            $form_input->formField()->associate($form_field);
            $form_input->fieldGroup()->associate($field_group);
            $form_input->group_instance_number = $group_instance_number;
        }

        $form_input->value = $array_value ? json_encode($form_input_value) : $form_input_value;
        $form_input->save();
    }

    public function getValueForInfo($value = null, bool $admin_link = false): string
    {
        return $value;
    }

    public function renderInfoList(Application $application, FormField $form_field, $value = null): string
    {
        $config_admin_model = config('paperless.models.admin');

        return TextEntry::make($form_field->name)
                        ->markAsRequired($form_field->is_required)
                        ->value($this->getValueForInfo($value, auth()->user() instanceof $config_admin_model))
                        ->toHtml();
    }

    public function isFilled(FormInput $formInput, Application $application, FormField $formField): bool
    {
        return filled($formInput->value);
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
        foreach ($field_types as $field_type) {
            if ($field_type::getValue() === $builder) {
                return new $field_type($form_field);
            }
        }

        throw new InvalidOperationException("Field type not found: {$builder}");
    }
}
