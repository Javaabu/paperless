<?php

namespace Javaabu\Paperless\Support\ValueObjects\Traits;

trait HasChild
{
    protected string | null $child = null;

    public function child(string $child): static
    {
        $this->child = $child;

        return $this;
    }
}
