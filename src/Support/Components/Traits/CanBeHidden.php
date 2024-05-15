<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait CanBeHidden
{
    protected bool $isHidden = false;

    public function markAsHidden(bool $condition = false): static
    {
        $this->isHidden = $condition;

        return $this;
    }

    public function hidden(bool $hide = true): static
    {
        $this->isHidden = $hide;

        return $this;
    }

    public function isHidden(): bool
    {
        return (bool) $this->isHidden;
    }
}
