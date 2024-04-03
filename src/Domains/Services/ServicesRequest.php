<?php

namespace Javaabu\Paperless\Domains\Services;

use Illuminate\Foundation\Http\FormRequest;

class ServicesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'code' => ['string', 'max:255'],
            'fee'  => ['numeric', 'between:0,999999'],
        ];

        if ($service = $this->route('service')) {
            $rules['code'][] = 'unique:services,code,' . $service->id;
        } else {
            $rules['name'][] = 'required';
            $rules['code'][] = 'required';
            $rules['code'][] = 'unique:services,code';
            $rules['fee'][] = 'required';
        }

        return $rules;
    }
}
