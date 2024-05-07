<?php

namespace Javaabu\Paperless\Support\InfoLists\Components;

use Illuminate\Support\Collection;
use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Models\FieldGroup;
use Javaabu\Paperless\Support\Components\Component;
use Javaabu\Paperless\Support\Components\Traits\CanBeMarkedAsRequired;

class RepeatingGroupInfo extends Component
{
    use CanBeMarkedAsRequired;

    protected string $view = 'paperless::view-components.repeating-group-info';

    protected ?FieldGroup $fieldGroup;
    protected Collection|null $formInputs;

    public function __construct()
    {
    }

    public static function make(): self
    {
        return new self();
    }

    // form Group
    public function fieldGroup(FieldGroup $field_group): self
    {
        $this->fieldGroup = $field_group;

        return $this;
    }

    public function formInputs(Collection|null $form_inputs): self
    {
        $this->formInputs = $form_inputs;

        return $this;
    }

    public function getFieldGroup(): ?FieldGroup
    {
        return $this->fieldGroup;
    }

    public function getFormInputs(): ?Collection
    {
        return $this->formInputs;
    }

    public function getHeadings(): ?Collection
    {
        return $this->fieldGroup->formFields->pluck('name');
    }

    public function getRows(): ?Collection
    {
        $rows = collect();
        $form_field_ids = $this->formInputs->unique('form_field_id')->pluck('form_field_id');

        $formFields = FormField::whereIn('id', $form_field_ids)->get();

        foreach ($this->formInputs->groupBy('group_instance_number') as $formInput) {
            $row = collect();
            foreach ($formFields as $formField) {
                $form_input = $formInput->where('form_field_id', $formField->id)->first()->value;
                $value = $formField->getBuilder()->getValueForInfo($form_input, true);

                $row->push($value);
            }

            $rows->push($row);
        }

        return $rows;
    }


}
