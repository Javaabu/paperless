@php
    $form_sections = $application_type->formSections->sortBy('order_column');
    $form_inputs = $application->formInputs;
    $form_fields = $application_type->formFields->sortBy('order_column');
@endphp

<x-forms::text :show-label="false" name="applicant_type" value="{{ request()->input('applicant_type') }}" :hidden="true" :required="true" />
<x-forms::text :show-label="false" name="applicant_id" value="{{ $applicant->id }}" :hidden="true" :required="true" />
<x-forms::text :show-label="false" name="application_type_id" value="{{ $application_type->id }}" :hidden="true" :required="true" />

{!! $application_type->render($applicant, $form_inputs) !!}
