<?php

namespace Javaabu\Paperless\Models;

use Javaabu\Paperless\Enums\Languages;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Support\Casts\FieldBuilderAttribute;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class FormField extends Model
{
    protected $attributes = [
        'language' => Languages::English,
    ];

    public function casts(): array
    {
        return [
            'builder'                     => FieldBuilderAttribute::class,
            'language'                    => Languages::class,
            'additional_validation_rules' => 'array',
            'options'                     => 'array',
        ];
    }


    public function formSection(): BelongsTo
    {
        return $this->belongsTo(FormSection::class);
    }

    public function fieldGroup(): BelongsTo
    {
        return $this->belongsTo(FieldGroup::class);
    }

    public function applicationType(): BelongsTo
    {
        return $this->belongsTo(ApplicationType::class);
    }

    public function validationRules(ApplicationType $application_type, Applicant $applicant, string $applicant_type, ?array $request_data = []): array
    {
        $default_validation_rules = $this->getDefaultValidationRules($applicant, $request_data);
        $additional_validation_rules = $this->getAdditionalValidationRules($application_type, $applicant, $applicant_type, $request_data);
        foreach ($default_validation_rules as $key => $value) {
            if ($key == $this->slug) {
                $default_validation_rules[$key] = array_merge($value, $additional_validation_rules);
            }
        }

        if (! array_key_exists($this->slug, $default_validation_rules) && ! empty($additional_validation_rules)) {
            $default_validation_rules[$this->slug] = $additional_validation_rules;
        }

        return $default_validation_rules;
    }

    public function getAdditionalValidationRules(ApplicationType $application_type, Applicant $applicant, string $applicant_type, ?array $request_data = []): array
    {
        $additional_rules = [];

        $additional_validation_rules = $this->normalizeAdditionalValidationRules();
        if (! empty($additional_validation_rules)) {
            $additional_rules = $this->buildAdditionalRules($additional_validation_rules, $applicant, $applicant_type, $request_data);
        }

        return $additional_rules;
    }

    public function buildAdditionalRules($additional_validation_rules, $applicant, $applicant_type, ?array $request_data = []): array
    {
        $additional_rules = [];
        foreach ($additional_validation_rules as $additional_validation_rule) {
            $validation_rule = trim($additional_validation_rule);
            $replace_placeholders = $this->replacePlaceHolders($validation_rule, $applicant, $applicant_type, $request_data);
            $additional_rules[] = $replace_placeholders;
        }

        return $additional_rules;
    }

    public function replacePlaceHolders($validation_rule, $applicant, $applicant_type, ?array $request_data = []): string
    {
        return str_replace(
            [
                '{individual_id}',
                '{academy_id}',
                '{academy_instructor_id}',
            ],
            [
                $applicant_type == 'individual' ? $applicant->id : null,
                $applicant_type == 'academy' ? $applicant->id : -1,
                $request_data['instructor'] ?? -1,
            ],
            $validation_rule
        );
    }

    public function normalizeAdditionalValidationRules()
    {
        $additional_validation_rules = $this->additional_validation_rules;
        if (is_string($additional_validation_rules)) {
            $additional_validation_rules = explode('|', $additional_validation_rules);
        }

        return $additional_validation_rules;
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        return $this->getBuilder()->getDefaultValidationRules($applicant, $request_data);
    }

    public function render(Applicant $entity, array | string $form_input = null, int | null $instance = null): string
    {
        $parameters = $this->type->getRenderParameters($this, $entity, $instance);
        $form_input = $this->getRenderedFieldValue($form_input);

        return $this->getBuilder()?->render($form_input, ...$parameters);
    }


    public function getBuilder(): IsComponentBuilder
    {
        return $this->type->getBuilderInstance($this);
    }


    public function getRenderedFieldValue($form_input): mixed
    {
        return match ($this->type->getSlug()) {
            default      => $form_input,
        };
    }

    public function renderInfoList($entity, $form_input = null): string
    {
        $value = $this->getFormInputValue($form_input);
        $builder = $this->getBuilder();
        return $builder?->renderInfoList($this, $value);
    }

    public function getFormInputValue($form_input = null): ?string
    {
        if (is_null($form_input)) {
            return '';
        }

        return match ($this->type->getSlug()) {
            default                                   => $form_input,
        };
    }

    public static function saveFormInputs(Application $application, array $form_inputs): void
    {
        $form_fields = $application->applicationType->formFields;
        foreach ($form_fields as $form_field) {
            $form_field->getBuilder()->saveInputs($application, $form_field, $form_inputs);
        }
    }
}
