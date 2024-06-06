<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\CanBeRepeated;

class ImageInput extends Field implements Htmlable
{
    use CanBeRepeated;

    protected string $view = 'paperless::field-components.image-input';

    protected string | null $child = null;

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
