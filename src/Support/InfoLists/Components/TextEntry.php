<?php

namespace Javaabu\Paperless\Support\InfoLists\Components;

use Javaabu\Paperless\Support\Components\Component;
use Javaabu\Paperless\Support\Components\Traits\CanBeMarkedAsRequired;

class TextEntry extends Component
{
    use CanBeMarkedAsRequired;

    protected string $view = 'paperless::view-components.text-entry';
    protected ?string $value;


    public function __construct(
        public string $label
    ) {
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function value(string $value = null): static
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
