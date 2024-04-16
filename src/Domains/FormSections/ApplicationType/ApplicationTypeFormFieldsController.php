<?php

namespace Javaabu\Paperless\Domains\FormSections\ApplicationType;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class ApplicationTypeFormFieldsController
{

    public function index(ApplicationType $application_type, Request $request)
    {
        $form_sections = $application_type->formSections->loadMissing('formFields', 'fieldGroups')->sortBy('order_column');
        return view('paperless::admin.form-fields.index', compact('application_type', 'form_sections'));
    }

}
