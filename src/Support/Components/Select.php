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


    public function __construct(
        public string $name
    ) {
    }

    public static function make(string $name): self
    {
        return new self($name);
    }
}
