<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Closure;
use Javaabu\Paperless\Support\Components\RepeatingGroup;

trait CanBeRepeated
{
    protected RepeatingGroup | Closure | null $repeatingGroup = null;
    protected bool | Closure | null $canBeRepeated            = false;

    protected int | Closure $repeatingInstance = 0;

    public function repeat(bool | Closure $canBeRepeated): static
    {
        $this->canBeRepeated = $canBeRepeated;

        return $this;
    }

    public function repeatingInstance(int | Closure | null $repeatingInstance): static
    {
        $this->repeatingInstance = $repeatingInstance ?? 0;

        return $this;
    }

    public function repeatingGroup(RepeatingGroup | Closure | null $repeating_group): static
    {
        $this->repeatingGroup = $repeating_group;

        return $this;
    }

    public function isRepeatable(): bool
    {
        if ($this->getRepeatingGroup() instanceof RepeatingGroup) {
            return true;
        }

        return $this->evaluate($this->canBeRepeated);
    }

    public function getRepeatingGroup(): ?RepeatingGroup
    {
        return $this->evaluate($this->repeatingGroup);
    }

    public function getRepeatingGroupId(): ?string
    {
        return $this->getRepeatingGroup()?->getId();
    }

    public function getRepeatingInstance()
    {
        return $this->evaluate($this->repeatingInstance);
    }
}
