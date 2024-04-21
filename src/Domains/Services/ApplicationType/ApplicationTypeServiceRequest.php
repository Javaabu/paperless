<?php

namespace Javaabu\Paperless\Domains\Services\ApplicationType;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationTypeServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $application_type = $this->route('application_type');

        return [
            'service' => [
                'required',
                'exists:services,id',
                'unique:application_type_service,service_id,NULL,id,application_type_id,' . $application_type->id,
            ],
            'is_applied_automatically' => ['nullable', 'boolean'],
        ];
    }
}
