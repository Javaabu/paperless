<?php

namespace Javaabu\Paperless\Support\Components\Traits;

trait HasPrependAndAppend
{
    protected string | null $prepend = null;

    protected string | null $append = null;

    public function prepend(null | string $prepend): self
    {
        $this->prepend = $prepend;

        return $this;
    }

    public function prefix(null | string $prefix): self
    {
        return $this->prepend($prefix);
    }

    public function getPrepend(): ?string
    {
        return $this->prepend;
    }

    public function getPrefix(): ?string
    {
        return $this->getPrepend();
    }

    public function append(null | string $append): self
    {
        $this->append = $append;

        return $this;
    }

    public function getAppend(): ?string
    {
        return $this->append;
    }

    public function getSuffix(): ?string
    {
        return $this->getAppend();
    }
}
