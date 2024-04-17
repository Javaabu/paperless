@php
    $form_sections = $application_type->formSections->sortBy('order_column');
    $form_inputs = $application->formInputs;
    $form_fields = $application_type->formFields->sortBy('order_column');
@endphp

{!! $application_type->render($applicant, $form_inputs) !!}
