<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Closure;

trait HasId
{
    protected string | Closure | null $id = null;

    public function id(string | Closure | null $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?string
    {
        $id = $this->evaluate($this->id);

        if (property_exists($this, 'canBeRepeated ')
            && method_exists($this, 'isRepeatable')
            && $this->isRepeatable()) {
            $id .= '_1';
        }

        if (! $id) {
            $id = $this->getName();
        }

        return $id;
    }
}
