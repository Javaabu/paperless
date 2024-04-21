<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait HasOptions
{
    protected array $options = [];

    public function options(array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }
}
