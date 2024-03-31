<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Closure;

trait HasDhivehiInput
{
    protected bool $is_dhivehi_input = false;

    public function dhivehi(Closure | bool $condition): static
    {
        $this->is_dhivehi_input = $condition;
        return $this;
    }

    public function hasDhivehiInput(): bool
    {
        return $this->evaluate($this->is_dhivehi_input);
    }
}
