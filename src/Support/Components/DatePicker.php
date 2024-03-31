<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\HasPlaceholder;

class DatePicker extends Field implements Htmlable
{
    use HasPlaceholder;
    protected string $view = 'paperless::field-components.date-picker';

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
