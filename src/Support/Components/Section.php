<?php

namespace Javaabu\Paperless\Support\Components;

use Closure;
use Javaabu\Paperless\Support\Components\Traits\HasHeading;
use Javaabu\Paperless\Support\Components\Traits\CanBeRemoved;
use Javaabu\Paperless\Support\Components\Traits\HasDescription;
use Javaabu\Paperless\Support\Components\Traits\HasContainerAttributes;
use Javaabu\Paperless\Support\Components\Traits\HasChildComponents;

class Section extends Component
{
    use HasChildComponents;
    use HasDescription;
    use HasHeading;
    use HasContainerAttributes;
    use CanBeRemoved;

    protected string $view = 'paperless::view-components.section';

    public function __construct(
        public string | Closure $heading,
    ) {
    }

    public static function make(string | Closure $heading): self
    {
        return app(static::class, ['heading' => $heading]);
    }

    public function getHeading(): string
    {
        return $this->evaluate($this->heading);
    }
}
