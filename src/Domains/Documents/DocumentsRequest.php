<?php

namespace Javaabu\Paperless\Domains\Documents;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Javaabu\Helpers\Media\AllowedMimeTypes;

class DocumentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowed_model_types = [
            'application',
        ];

        $document = $this->route('document');
        $model_type = $document ? $document->model_type : $this->input('model_type');

        $rules = [
            'file'          => [
                'mimetypes:' . AllowedMimeTypes::getAllowedMimeTypesString('document'),
                'max:' . get_setting('max_upload_file_size'),
            ],
            'name'          => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'model_type'    => ['string', Rule::in($allowed_model_types)],
            'model_id'      => ['integer'],
            'document_type' => ['nullable', Rule::exists('document_types', 'id')],
        ];

        if ($model_type && in_array($model_type, $allowed_model_types)) {
            // get the table
            $class = Model::getActualClassNameForMorph($model_type);
            $instance = (new $class());
            $rules['model_id'][] = Rule::exists($instance->getTable(), $instance->getKeyName());
        }

        if ($document) {
            //
        } else {
            $rules['file'][] = 'required';
            $rules['model_type'][] = 'required';
            $rules['model_id'][] = 'required';
        }

        return $rules;
    }
}
