@php
    $form_sections = $application_type->formSections->sortBy('order_column');
    $form_inputs = $application->formInputs;
    $form_fields = $application_type->formFields->sortBy('order_column');
@endphp

{!! Form::hidden('applicant_type_id', request()->input('applicant_type')) !!}
{!! Form::hidden('applicant_id', $applicant->id) !!}
{!! Form::hidden('application_type_id', $application_type->id) !!}

{!! $application_type->render($applicant, $form_inputs) !!}



