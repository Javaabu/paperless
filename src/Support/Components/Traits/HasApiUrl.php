<?php

namespace Javaabu\Paperless\Support\Components\Traits;

use Closure;

trait HasApiUrl
{
    protected string | Closure | null $api_url = null;

    public function apiUrl(string | Closure | null $api_url): static
    {
        $this->api_url = $api_url;

        return $this;
    }

    public function getApiUrl(): ?string
    {
        return $this->evaluate($this->api_url);
    }
}
