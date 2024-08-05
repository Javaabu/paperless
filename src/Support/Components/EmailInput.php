<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\ValueObjects\Traits\HasChild;
use Javaabu\Paperless\Support\Components\Traits\HasInputType;
use Javaabu\Paperless\Support\Components\Traits\CanBeRepeated;
use Javaabu\Paperless\Support\Components\Traits\HasPlaceholder;
use Javaabu\Paperless\Support\Components\Traits\HasPrependAndAppend;
use Javaabu\Paperless\Support\ValueObjects\Traits\HasApiHelperFields;
use Javaabu\Paperless\Support\ValueObjects\Traits\HasConditionalDisplay;

class EmailInput extends Field implements Htmlable
{
    use CanBeRepeated;
    use HasApiHelperFields;
    use HasChild;
    use HasConditionalDisplay;
    use HasInputType;
    use HasPlaceholder;
    use HasPrependAndAppend;

    protected string $view = 'paperless::field-components.email-input';

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
