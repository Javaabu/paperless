<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait CanMultiSelect
{
    protected bool $isMultiple = false;

    public function multiple(bool $condition = false): static
    {
        $this->isMultiple = $condition;

        return $this;
    }

    public function isMultiple(): bool
    {
        return (bool) $this->isMultiple;
    }
}
