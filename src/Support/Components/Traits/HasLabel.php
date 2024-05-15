<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait HasLabel
{
    use CanBeHidden;

    protected string | null $label = null;

    public function label(string | null $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label ?? str($this->getName())->replace('_', ' ')->title()->__toString();
    }

    public function labelShouldBeHidden(): bool
    {
        if (property_exists($this, 'isHidden')) {
            return ! $this->isHidden();
        }

        return true;
    }
}
