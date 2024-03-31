<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Closure;

trait HasState
{
    protected array | string | Closure | null $state = null;

    public function state(array | string | Closure | null $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getState(): array | string | null
    {
        return $this->evaluate($this->state);
    }
}
