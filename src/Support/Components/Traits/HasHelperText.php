<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait HasHelperText
{
    protected string | null $placeholder = null;

    public function placeholder(string | null $placeholder): static
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder ?? null;
    }
}
