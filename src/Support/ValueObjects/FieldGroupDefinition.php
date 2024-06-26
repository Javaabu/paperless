<?php

namespace Javaabu\Paperless\Support\ValueObjects;

class FieldGroupDefinition
{
    private string | null $label = null;
    private string | null $description = null;
    private array | null $fields = [];
    private int | null $order_column = null;
    private string | null $add_more_button = null;

    public function __construct(
        private readonly ?string $slug,
    ) {
    }

    public static function make(string $slug): self
    {
        return new self($slug);
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function fields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function orderColumn(int $order_column): self
    {
        $this->order_column = $order_column;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getOrderColumn(): int | null
    {
        return $this->order_column;
    }

    public function addMoreButton(string $value): self
    {
        $this->add_more_button = $value;

        return $this;
    }

    public function getAddMoreButton(): ?string
    {
        return $this->add_more_button;
    }
}
