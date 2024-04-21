<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait HasChildComponents
{
    protected string $childComponents;

    public function schema(string $components): static
    {
        $this->childComponents = $components;

        return $this;
    }

    public function getChildComponents(): string
    {
        return $this->childComponents ?? '';
    }

}
