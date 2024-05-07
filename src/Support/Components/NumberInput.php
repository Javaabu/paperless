<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\HasInputType;
use Javaabu\Paperless\Support\Components\Traits\CanBeRepeated;
use Javaabu\Paperless\Support\Components\Traits\HasPlaceholder;

class NumberInput extends Field implements Htmlable
{
    use CanBeRepeated;
    use HasInputType;
    use HasPlaceholder;


    protected string $view = 'paperless::field-components.number-input';

    public function __construct(
        string $name
    ) {
        $this->name = str($name)->slug('_')->__toString();
    }

    public static function make(string $name): self
    {
        return new self($name);
    }
}
