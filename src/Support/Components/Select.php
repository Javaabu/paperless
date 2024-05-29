<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\HasOptions;
use Javaabu\Paperless\Support\Components\Traits\CanBeRepeated;
use Javaabu\Paperless\Support\Components\Traits\CanMultiSelect;
use Javaabu\Paperless\Support\Components\Traits\HasPlaceholder;

class Select extends Field implements Htmlable
{
    use CanBeRepeated;
    use CanMultiSelect;
    use HasOptions;
    use HasPlaceholder;

    protected string $view = 'paperless::field-components.select';
    protected string|null $child = null;
    protected bool $with_hidden_input_helpers = false;

    public function __construct(
        public string $name
    ) {
    }

    public static function make(string $name): self
    {
        return new self($name);
    }


    public function child(string|null $child): static
    {
        $this->child = $child;

        return $this;
    }

    public function getChild(): ?string
    {
        return $this->child ?? '';
    }

    public function withHiddenInputHelpers(): static
    {
        $this->with_hidden_input_helpers = true;

        return $this;
    }

    public function getHiddenInputHelperClassName(): ?string
    {
        if ($this->with_hidden_input_helpers) {
            return $this->name . '_helpers';
        }

        return null;
    }
}
