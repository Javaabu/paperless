<?php

namespace Javaabu\Paperless\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Support\Components\Section;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Javaabu\Paperless\Support\Components\RepeatingGroup;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;
use Javaabu\Paperless\Support\InfoLists\Components\RepeatingGroupInfo;

class FieldGroup extends Model
{
    use HasFactory;

    public function formSection(): BelongsTo
    {
        return $this->belongsTo(FormSection::class);
    }

    public function applicationType(): BelongsTo
    {
        return $this->belongsTo(ApplicationType::class);
    }


    public function formFields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function render(Entity|Individual $entity, Collection|null $form_inputs = null): string
    {
        $this->loadMissing('formFields');
        $form_fields = $this->formFields->sortBy('order_column');

        $field_group_form_inputs = $form_inputs?->where('field_group_id', $this->id)->groupBy('group_instance_number');
        $fields_html             = $this->generateFieldsHtml($form_fields, $entity);

        $fields_section_html = Section::make($this->name)
                                      ->containerClass('repeater')
                                      ->containerId('repeater-0')
                                      ->canBeRemoved(true)
                                      ->schema($fields_html)
                                      ->toHtml();

        $old_data = collect(old($this->slug));

        if ($old_data->isNotEmpty()) {
            $repeating_group = $this->generateRepeatingGroupHtml($old_data, $form_fields, $entity);

            return RepeatingGroup::make($this->name)
                                 ->id($this->slug)
                                 ->schema($repeating_group)
                                 ->repeatingSchema($fields_section_html)
                                 ->toHtml();
        }

        if ($field_group_form_inputs->isEmpty()) {

            return RepeatingGroup::make($this->name)
                                 ->id($this->slug)
                                 ->schema($fields_section_html)
                                 ->repeatingSchema($fields_section_html)
                                 ->toHtml();
        }


        if ($field_group_form_inputs->isNotEmpty() && $old_data->isEmpty()) {
            $repeating_group = $this->generateRepeatingGroupHtml($field_group_form_inputs, $form_fields, $entity);

            return RepeatingGroup::make($this->name)
                                 ->id($this->slug)
                                 ->schema($repeating_group)
                                 ->repeatingSchema($fields_section_html)
                                 ->toHtml();
        }

        return '';
    }

    public function renderInfoList(Individual|Entity $entity, Collection|null $form_inputs = null): string
    {
        return RepeatingGroupInfo::make()
                                 ->fieldGroup($this)
                                 ->formInputs($form_inputs->where('field_group_id', $this->id))
                                 ->toHtml();
    }

    public function generateFieldsHtml($form_fields, $entity): string
    {
        $fields_html = '';
        foreach ($form_fields as $form_field) {
            $form_field->setRelation('fieldGroup', $this);
            $fields_html .= $form_field->render($entity);
        }

        return $fields_html;
    }

    public function generateRepeatingGroupHtml($field_group_form_inputs, $form_fields, $entity): string
    {
        $repeating_group = '';
        foreach ($field_group_form_inputs as $key => $group_form_inputs) {
            $field_html = '';
            foreach ($form_fields as $form_field) {
                $form_input = null;
                if (is_array($group_form_inputs)) {
                    $gov_id = data_get($group_form_inputs, 'gov_id');
                    $name   = data_get($group_form_inputs, 'name');

                    $individual = Individual::where('gov_id', $gov_id)->where('name', $name)->first();
                    $form_input = $individual?->id;

                    if (! $individual && $form_field->slug == 'student') {
                        $form_input = [
                            'name'               => $name,
                            'name_dv'            => data_get($group_form_inputs, 'name_dv'),
                            'nationality'        => data_get($group_form_inputs, 'nationality'),
                            'gov_id'             => $gov_id,
                            'gender'             => data_get($group_form_inputs, 'gender'),
                            'certificate_number' => data_get($group_form_inputs, 'certificate_number'),
                        ];
                    }

                    if ($form_field->slug == 'certificate_number') {
                        $form_input = data_get($group_form_inputs, 'certificate_number');
                    }

                } else {
                    $form_input = $group_form_inputs->where('form_field_id', $form_field->id)->where('group_instance_number', $key)->first()?->value;
                }

                $form_field->setRelation('fieldGroup', $this);
                $field_html .= $form_field->render($entity, $form_input, $key);
            }

            $repeating_group .= Section::make($this->name)
                                       ->containerClass('repeater')
                                       ->containerId('repeater-' . $key)
                                       ->canBeRemoved(true)
                                       ->schema($field_html)
                                       ->toHtml();

        }

        return $repeating_group;
    }
}
