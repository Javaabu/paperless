<?php

namespace Javaabu\Paperless\Support\Builders;

use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\ApiSelect;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class ApiSelectBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public function render(Model|string|null $input = null, ?string $api_url = '', ?array $filter_by = [])
    {
        return ApiSelect::make($this->form_field->slug)
                        ->label($this->form_field->name)
                        ->apiUrl($api_url)
                        ->markAsRequired($this->form_field->is_required)
                        ->filterBy($filter_by)
                        ->selected($input)
                        ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = [], ?int $iteration = null): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';

        return [
            $this->form_field->slug => [$is_required, 'string', 'max:255'],
        ];
    }
}
