<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait CanBeMarkedAsRequired
{
    protected bool $isMarkedAsRequired = false;

    public function markAsRequired(bool $condition = false): static
    {
        $this->isMarkedAsRequired = $condition;

        return $this;
    }

    public function isMarkedAsRequired(): bool
    {
        return (bool) $this->isMarkedAsRequired;
    }
}
