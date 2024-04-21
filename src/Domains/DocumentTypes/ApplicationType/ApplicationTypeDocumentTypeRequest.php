<?php

namespace Javaabu\Paperless\Domains\DocumentTypes\ApplicationType;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationTypeDocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $application_type = $this->route('application_type');

        return [
            'document_type' => ['required', 'exists:document_types,id', 'unique:document_type_application_type,document_type_id,NULL,id,application_type_id,' . $application_type->id],
            'is_required' => ['nullable', 'boolean'],
        ];
    }
}
