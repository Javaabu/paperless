<?php

namespace Javaabu\Paperless\Support\ValueObjects;

class FieldDefinition
{
    private string | null $label = null;
    private int | null $order_column = null;
    private string | null $language = 'en';
    private string | null $placeholder = null;
    private string | null $builder = null;
    private bool $is_required = false;
    private string | null $additional_validation_rules = '';
    private string | null $options = '';

    public function __construct(
        private readonly ?string $slug,
    ) {
    }

    public static function make(string $slug): self
    {
        return new self($slug);
    }

    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function orderColumn(string $order_column): self
    {
        $this->order_column = $order_column;
        return $this;
    }

    public function language(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function builder(string $builder): self
    {
        $this->builder = $builder;
        return $this;
    }

    public function isRequired(bool $is_required = true): self
    {
        $this->is_required = $is_required;
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

    public function getOrderColumn(): string
    {
        return $this->order_column;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function getBuilder(): string
    {
        return $this->builder;
    }

    public function getIsRequired(): string
    {
        return $this->is_required;
    }
}
