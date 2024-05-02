<?php

namespace Javaabu\Paperless\Exceptions;

use Throwable;

class ApplicationStatusEnumDoesNotImplement extends \Exception
{
    public function __construct(
        string $message = "Your configured Application Status Enum does not implement the required IsApplicationStatusesEnum interface.",
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

}
