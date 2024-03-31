<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use App\Models\Institution;
use App\Helpers\Enums\ExaminerStatuses;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Support\Components\ApiSelect;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class CountryBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public function render(Model | string | null $input = null, string | null $api_url = '', array | null $filter_by = [])
    {
        return ApiSelect::make($this->form_field->slug)
                        ->label($this->form_field->name)
                        ->apiUrl($api_url)
                        ->markAsRequired($this->form_field->is_required)
                        ->filterBy($filter_by)
                        ->selected($input)
                        ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';
        return [
            $this->form_field->slug => [
                $is_required,
                'exists:countries,id',
            ],
        ];
    }
}
