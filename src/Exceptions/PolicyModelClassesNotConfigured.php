<?php

namespace Javaabu\Paperless\Exceptions;

use Throwable;

class PolicyModelClassesNotConfigured extends \Exception
{
    public function __construct(
        string $message = "You are missing some model classes needed for your policies. Please add then to the paperless.php config file and try again.",
        int $code = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

}
