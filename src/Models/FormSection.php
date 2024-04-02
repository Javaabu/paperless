<?php

namespace Javaabu\Paperless\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Support\Components\Section;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSection extends Model
{
    use HasFactory;


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

    public function scopeWhereHasApplication($query, $application_id): void
    {
        $query->whereHas('applicationType', function ($query) use ($application_id) {
            $query->whereHas('applications', function ($query) use ($application_id) {
                $query->where('id', $application_id);
            });
        });
    }

    public function render(Entity|Individual $entity, Collection | null $form_inputs = null, bool $with_card = true): string
    {
        $fields_html = '';
        $this->loadMissing('formFields', 'fieldGroups');
        $form_fields = $this->formFields->whereNull('field_group_id')->sortBy('order_column');

        foreach ($form_fields as $form_field) {
            $form_input = $form_inputs->where('form_field_id', $form_field->id)->first()?->value;
            $fields_html .= $form_field->render($entity, $form_input);
        }

        if ($with_card) {
            $form_section = Section::make($this->name)
                                   ->description($this->description)
                                   ->schema($fields_html)
                                   ->toHtml();
        } else {
            $form_section = $fields_html;
        }

        $field_groups = $this->fieldGroups->sortBy('order_column');
        $field_group_html = '';
        foreach ($field_groups as $field_group) {
            $field_group_html .= $field_group->render($entity, $form_inputs);
        }

        return $form_section . $field_group_html;
    }

    public function renderInfoList(Entity|Individual $entity, Collection | null $form_inputs = null, bool $with_card = true): string
    {
        $fields_html = '';

        $this->loadMissing('formFields', 'fieldGroups');
        $form_fields = $this->formFields->whereNull('field_group_id')->sortBy('order_column');

        foreach ($form_fields as $form_field) {
            $form_input = $form_inputs->where('form_field_id', $form_field->id)->first()?->value;
            $fields_html .= $form_field->renderInfoList($entity, $form_input);
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
