<?php

namespace Javaabu\Paperless\Domains\DocumentTypes;

use Illuminate\Foundation\Http\FormRequest;

class DocumentTypesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'slug' => ['string', 'max:255'],
            'description' => ['nullable', 'string', 'max:65535'],
        ];

        if ($document_type = $this->route('document_type')) {
            $rules['slug'][] = 'unique:document_types,slug,' . $document_type->id;
        } else {
            $rules['name'][] = 'required';
            $rules['slug'][] = 'required';
            $rules['slug'][] = 'unique:document_types,slug';
        }

        return $rules;
    }
}
