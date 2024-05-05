<?php

namespace Javaabu\Paperless\Support\Components;

use Closure;
use Javaabu\Paperless\Support\Components\Traits\HasId;
use Javaabu\Paperless\Support\Components\Traits\HasHeading;
use Javaabu\Paperless\Support\Components\Traits\HasDescription;
use Javaabu\Paperless\Support\Components\Traits\HasChildComponents;
use Javaabu\Paperless\Support\Components\Traits\HasRepeatingChildComponents;

class RepeatingGroup extends Component
{
    use HasChildComponents;
    use HasDescription;
    use HasHeading;
    use HasId;
    use HasRepeatingChildComponents;

    protected string $view = 'paperless::view-components.repeating-group';

    protected string $addMoreButtonName = 'Add More';

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

    public function addMoreButtonName(?string $value): static
    {
        $this->addMoreButtonName = $value;

        return $this;
    }

    public function getAddMoreButtonName(): string
    {
        return $this->addMoreButtonName;
    }
}
