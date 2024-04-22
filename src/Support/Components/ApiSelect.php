<?php

namespace Javaabu\Paperless\Support\Components;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\HasApiUrl;
use Javaabu\Paperless\Support\Components\Traits\HasOptions;
use Javaabu\Paperless\Support\Components\Traits\CanMultiSelect;
use Javaabu\Paperless\Support\Components\Traits\HasPlaceholder;

class ApiSelect extends Field implements Htmlable
{
    use CanMultiSelect;
    use HasApiUrl;
    use HasOptions;
    use HasPlaceholder;

    protected string $view = 'paperless::field-components.api-select';
    protected string | Closure | null $name_field = 'name';
    protected Model | Closure | string | null $selected = null;
    protected array | Closure | null $filter_by = null;

    public function __construct(
        public string $name
    ) {
    }

    public function nameField(string | Closure | null $name_field): static
    {
        $this->name_field = $name_field;

        return $this;
    }

    public function getNameField(): ?string
    {
        return $this->evaluate($this->name_field);
    }

    public function selected(string | Model | Closure | null $selected): static
    {
        $this->selected = $selected;

        return $this;
    }

    public function getSelected(): Model|string|null
    {
        return $this->evaluate($this->selected);
    }

    public function filterBy(array | Closure | null $filter_by): static
    {
        $this->filter_by = $filter_by;

        return $this;
    }

    public function getFilterBy(): null|array|string
    {
        $evaluated = $this->evaluate($this->filter_by);

        if (count($evaluated) === 1) {
            return $evaluated[0];
        }

        return $evaluated;
    }

    public static function make(string $name): self
    {
        return new self($name);
    }
}
