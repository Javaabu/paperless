<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\HasOptions;
use Javaabu\Paperless\Support\Components\Traits\CanMultiSelect;
use Javaabu\Paperless\Support\Components\Traits\HasPlaceholder;

class Select extends Field implements Htmlable
{
    use CanMultiSelect;
    use HasOptions;
    use HasPlaceholder;

    protected string $view = 'paperless::field-components.select';
    protected string|null $child = null;

    public function __construct(
        public string $name
    )
    {
    }

    public static function make(string $name): self
    {
        return new self($name);
    }


    public function child(string|null $child): static
    {
        $this->child = $child;

        return $this;
    }

    public function getChild(): ?string
    {
        return $this->child ?? '';
    }
}
