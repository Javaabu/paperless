<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait HasContainerAttributes
{
    protected string $containerId    = '';
    protected string $containerClass = '';

    public function containerId(string $id): static
    {
        $this->containerId = $id;

        return $this;
    }

    public function containerClass(string $class): static
    {
        $this->containerClass = $class;

        return $this;
    }

    public function getContainerId(): string
    {
        return $this->containerId;
    }

    public function getContainerClass(): string
    {
        return $this->containerClass;
    }

}
