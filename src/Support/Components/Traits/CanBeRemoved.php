<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Closure;

trait CanBeRemoved
{
    protected bool | Closure $canBeRemoved = false;

    public function canBeRemoved(bool | Closure $canBeRemoved = true): static
    {
        $this->canBeRemoved = $canBeRemoved;

        return $this;
    }

    public function getCanBeRemoved(): bool
    {
        return $this->evaluate($this->canBeRemoved);
    }
}
