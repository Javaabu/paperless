<?php

namespace Javaabu\Paperless\Models;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Section;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class FormSection extends Model
{
    use HasFactory;

    public function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function applicationType(): BelongsTo
    {
        return $this->belongsTo(ApplicationType::class);
    }

    public function formFields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function fieldGroups(): HasMany
    {
        return $this->hasMany(FieldGroup::class);
    }

    public function isConditional(): bool
    {
        return data_get($this->meta, 'conditional_on', false)
            && data_get($this->meta, 'conditional_value', false);
    }

    public function scopeWhereHasApplication($query, $application_id): void
    {
        $query->whereHas('applicationType', function ($query) use ($application_id) {
            $query->whereHas('applications', function ($query) use ($application_id) {
                $query->where('id', $application_id);
            });
        });
    }

    /**
     * Render the FormSection and all of it's FormFields
     *
     * @param  Application      $application
     * @param  Applicant        $entity
     * @param  Collection|null  $form_inputs
     * @param  bool             $with_card
     * @return string
     * @throws Exception
     */
    public function render(Application $application, Applicant $entity, Collection | null $form_inputs = null, bool $with_card = true): string
    {
        // Initialize the HTML string
        $fields_html = '';

        // Load in the relations
        $this->loadMissing('formFields', 'fieldGroups');

        // Get all the FormFields that belong to this FormSection, but filter out anything that belongs to a FieldGroup
        $form_fields = $this->formFields->whereNull('field_group_id')->sortBy('order_column');

        /** @var FormField $form_field */
        foreach ($form_fields as $form_field) {
            /**
             * Get the FormInput for this FormField. It's being done like this because the $formInputs are passed in
             * from the ApplicationType render method.
             */
            $form_input = $form_inputs->where('form_field_id', $form_field->id)->first()?->value;

            // Passing in the FormInput, render the FormField and append to the HTML string
            $fields_html .= $form_field->render($application, $entity, $form_input);
        }

        // If the $with_card is true, wrap the FormSection in a Section component, which will render it inside a card.
        if ($with_card) {
            $form_section =
                Section::make($this->name)
                       ->description($this->description)
                       ->schema($fields_html); // Schema here just refers to the rendered HTML string for the FormFields

            // If this whole section is conditional, then wrap it in a conditional display wrapper
            if ($this->isConditional()) {
                $form_section->conditionalOn($this->meta['conditional_on'], $this->meta['conditional_value']);
            }

            $form_section = $form_section->toHtml();
        } else {
            $form_section = $fields_html;
        }

        /**
         * And now we render the FieldGroups that are on this FormSection.
         */
        // Get all the FieldGroups of this FormSection
        $field_groups = $this->fieldGroups->sortBy('order_column');

        // Initialize a FieldGroup HTML string
        $field_group_html = '';

        /** @var FieldGroup $field_group */
        foreach ($field_groups as $field_group) {
            // Passing in the whole of $form_inputs, Render the FieldGroup and append to the FieldGroup HTML string
            $field_group_html .= $field_group->render($application, $entity, $form_inputs);
        }

        return $form_section . $field_group_html;
    }

    public function renderInfoList(Application $application, Applicant $entity, Collection | null $form_inputs = null, bool $with_card = true): string
    {
        $fields_html = '';

        $this->loadMissing('formFields', 'fieldGroups');
        $form_fields = $this->formFields->whereNull('field_group_id')->sortBy('order_column');

        foreach ($form_fields as $form_field) {
            $form_input = $form_inputs->where('form_field_id', $form_field->id)->first()?->value;
            $fields_html .= $form_field->renderInfoList($application, $entity, $form_input);
        }

        if ($with_card) {
            $form_section = Section::make($this->name)
                                   ->schema($fields_html)
                                   ->toHtml();
        } else {
            $form_section = $fields_html;
        }


        $field_groups = $this->fieldGroups->sortBy('order_column');
        $field_groups_html = '';
        foreach ($field_groups as $field_group) {
            $field_group_html = $field_group->renderInfoList($entity, $form_inputs);
            $field_groups_html .= Section::make($field_group->name)
                                         ->schema($field_group_html)
                                         ->toHtml();
        }


        return $form_section . $field_groups_html;
    }
}
