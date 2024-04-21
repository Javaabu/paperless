<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationTypesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description'  => ['nullable', 'string', 'max:65535'],
            'eta_duration' => ['integer', 'min:1', 'max:999999'],
        ];
    }
}
