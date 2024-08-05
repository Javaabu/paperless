<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;

class Checkbox extends Field implements Htmlable
{
    protected string $view = 'paperless::field-components.checkbox';

    public function __construct(
        string $name
    )
    {
        $this->name = str($name)->slug('_')->__toString();
    }

    public static function make(string $name): self
    {
        return new self($name);
    }
}
