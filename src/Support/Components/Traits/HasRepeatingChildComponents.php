<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Illuminate\Support\Collection;

trait HasRepeatingChildComponents
{
    protected string $repeatingSchema = '';


    public function repeatingSchema(string $schema): self
    {
        $this->repeatingSchema = $schema;
        return $this;
    }

    public function getRepeatingSchema(): string
    {
        return $this->repeatingSchema;
    }
}
