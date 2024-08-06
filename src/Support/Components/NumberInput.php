<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\HasInputType;
use Javaabu\Paperless\Support\Components\Traits\CanBeRepeated;
use Javaabu\Paperless\Support\Components\Traits\HasPlaceholder;
use Javaabu\Paperless\Support\Components\Traits\HasPrependAndAppend;
use Javaabu\Paperless\Support\ValueObjects\Traits\HasConditionalDisplay;

class NumberInput extends Field implements Htmlable
{
    use CanBeRepeated;
    use HasConditionalDisplay;
    use HasInputType;
    use HasPlaceholder;
    use HasPrependAndAppend;

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
