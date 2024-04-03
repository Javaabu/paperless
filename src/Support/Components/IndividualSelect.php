<?php

namespace Javaabu\Paperless\Support\Components;

use App\Models\Individual;
use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\CanBeRepeated;

class IndividualSelect extends Field implements Htmlable
{
    use CanBeRepeated;

    protected string $view = 'paperless::field-components.individual-select';
    protected Individual | null $individual = null;

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function getIndividual(): ?Individual
    {
        return Individual::where('id', $this->getState())?->first();
    }

}
