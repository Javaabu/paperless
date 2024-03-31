<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Closure;

trait EvaluatesClosures
{
    protected function evaluate($value)
    {
        if (! $value instanceof Closure) {
            return $value;
        }

        return app()->call($value);
    }
}
