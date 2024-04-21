<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait HasName
{
    protected string $name;

    public function name(string $name): static
    {
        $this->name = str($name)->slug('_')->__toString();

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
