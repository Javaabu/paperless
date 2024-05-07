<?php

namespace Javaabu\Paperless\Support\InfoLists\Components;

use App\Models\FieldGroup;
use Illuminate\Support\Collection;
use Javaabu\Paperless\Support\Components\Component;
use Javaabu\Paperless\Support\Components\Traits\CanBeMarkedAsRequired;

class RepeatingGroupInfo extends Component
{
    use CanBeMarkedAsRequired;

    protected string $view = 'paperless::view-components.repeating-group-info';

    protected ?FieldGroup $fieldGroup;
    protected Collection | null $formInputs;

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

    public function formInputs(Collection | null $form_inputs): self
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


}
