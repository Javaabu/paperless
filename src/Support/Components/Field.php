<?php

namespace Javaabu\Paperless\Support\Components;

use Javaabu\Paperless\Support\Components\Traits\HasId;
use Javaabu\Paperless\Support\Components\Traits\HasName;
use Javaabu\Paperless\Support\Components\Traits\HasLabel;
use Javaabu\Paperless\Support\Components\Traits\HasState;
use Javaabu\Paperless\Support\Components\Traits\HasHelperText;
use Javaabu\Paperless\Support\Components\Traits\CanBeValidated;
use Javaabu\Paperless\Support\Components\Traits\HasDhivehiInput;
use Javaabu\Paperless\Support\Components\Traits\CanBeMarkedAsRequired;
use Javaabu\Paperless\Support\Components\Interfaces\FieldTypeInterface;

abstract class Field extends Component implements FieldTypeInterface
{
    use CanBeMarkedAsRequired;
    use CanBeValidated;
    use HasDhivehiInput;
    use HasHelperText;
    use HasId;
    use HasLabel;
    use HasName;
    use HasState;

    public function getValidationRules(): array
    {
        return [];
    }
}
