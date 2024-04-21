<?php

namespace Javaabu\Paperless\Support\ValueObjects;

class SectionDefinition
{
    private string | null $label = null;
    private string | null $description = null;
    private array | null $fields = [];
    private array | null $field_groups = [];
    private bool $is_admin_section = false;

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

    public function fieldGroups(array $field_groups): self
    {
        $this->field_groups = $field_groups;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function isAdminSection(): bool
    {
        $this->is_admin_section = true;

        return $this->is_admin_section;
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

    public function getFieldGroups(): array
    {
        return $this->field_groups;
    }

    public function getIsAdminSection(): bool
    {
        return $this->is_admin_section;
    }
}
