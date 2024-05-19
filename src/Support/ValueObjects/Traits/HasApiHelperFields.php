<?php

namespace Javaabu\Paperless\Support\ValueObjects\Traits;

trait HasApiHelperFields
{
    private bool $generate_hidden_input_helper_class = false;
    private string | null $helper_for = '';
    private string | null $api_url = '';
    private string | null $helper_target_column = '';


    public function withHiddenInputHelpers(): self
    {
        $this->generate_hidden_input_helper_class = true;

        return $this;
    }

    public function helperFor(string $helper_for): self
    {
        $this->helper_for = $helper_for;

        return $this;
    }

    public function getHelperForClass(): string
    {
        return $this->helper_for . '_helpers';
    }

    public function helperApiUrl(string $api_url): static
    {
        $this->api_url = $api_url;

        return $this;
    }

    public function getHelperApiUrl(): ?string
    {
        return $this->api_url;
    }

    public function helperTargetColumn(string $target_column): static
    {
        $this->helper_target_column = $target_column;

        return $this;
    }

    public function getHelperTargetColumn(): ?string
    {
        return $this->helper_target_column;
    }
}
