<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Closure;

trait HasInputType
{
    protected Closure | string $type = 'text';

    public function type(Closure | string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->evaluate($this->type);
    }
}
