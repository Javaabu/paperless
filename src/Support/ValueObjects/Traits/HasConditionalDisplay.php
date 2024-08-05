<?php

namespace Javaabu\Paperless\Support\ValueObjects\Traits;

trait HasConditionalDisplay
{
    private string | null $conditional_on = '';
    private string | int | bool | null $conditional_value = '';

    private bool $conditional_hidden = false;

    private bool $conditional_checkbox = false;

    public function conditionalOn(string $field, string | int $value, bool $hidden = false, bool $checkbox = false): self
    {
        $this->conditional_on = $field;
        $this->conditional_value = $value;
        $this->conditional_hidden = $hidden;
        $this->conditional_checkbox = $checkbox;

        return $this;
    }

    public function getConditionalOn(): ?string
    {
        return $this->conditional_on;
    }

    public function getConditionalValue(): int | string | null
    {
        if ($this->conditional_value === 1) {
            return true;
        }

        return $this->conditional_value;
    }

    public function isConditional(): bool
    {
        return
            $this->conditional_on || $this->conditional_value;
    }

    public function isHiddenUntilSelect(): bool
    {
        return $this->conditional_hidden;
    }

    public function isReversedConditional(): bool
    {
        return ! filled($this->conditional_value);
    }

    public function isConditionalCheckbox(): bool
    {
        return $this->conditional_checkbox;
    }
}
