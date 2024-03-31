<?php

namespace Javaabu\Paperless\Support\InfoLists\Components;

use Javaabu\Paperless\Support\Components\Component;
use Javaabu\Paperless\Support\Components\Traits\CanBeMarkedAsRequired;

class DocumentLister extends Component
{
    use CanBeMarkedAsRequired;

    protected string $view = 'paperless::view-components.document-lister';
    protected ?string $value;
    protected ?string $size;
    protected ?string $file_name;
    protected ?string $download_link;


    public function __construct(
        public string $label
    ) {
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function size(?string $size = null): self
    {
        $this->size = $size;
        return $this;
    }

    public function fileName(?string $file_name = null): self
    {
        $this->file_name = $file_name;
        return $this;
    }

    public function downloadLink(?string $link = null): self
    {
        $this->download_link = $link;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function getDownloadLink(): ?string
    {
        return $this->download_link ?? '#';
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function value(string $value = null): static
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
