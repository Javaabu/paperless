<?php

namespace Javaabu\Paperless\Support\Components;

use Illuminate\Contracts\Support\Htmlable;
use Javaabu\Paperless\Support\Components\Traits\HasInputType;
use Javaabu\Paperless\Support\Components\Traits\CanBeRepeated;
use Javaabu\Paperless\Support\Components\Traits\HasPlaceholder;

class TextInput extends Field implements Htmlable
{
    use CanBeRepeated;
    use HasInputType;
    use HasPlaceholder;

    protected string $view = 'paperless::field-components.text-input';

    protected string | null $child = null;

    protected bool $helper_field = false;

    protected string | null $api_url = null;

    protected string | null $api_target_column = null;

    protected string | null $prefix = null;

    public function __construct(
        string $name
    )
    {
        $this->name = str($name)->slug('_')->__toString();
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function child(string $child)
    {
        $this->child = $child;

        return $this;
    }

    public function getChildSelector()
    {
        return $this->child;
    }

    public function isHelperField()
    {
        $this->helper_field = true;

        return $this;
    }

    public function apiUrl(string $api_url)
    {
        $this->api_url = $api_url;

        return $this;
    }

    public function getApiUrl()
    {
        return $this->api_url;
    }

    public function getIsHelperField()
    {
        return $this->helper_field;
    }

    public function apiTargetColumn(string $target)
    {
        $this->api_target_column = $target;

        return $this;
    }

    public function getApiTargetColumn()
    {
        return $this->api_target_column;
    }

    public function prefix(null | string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }
}
