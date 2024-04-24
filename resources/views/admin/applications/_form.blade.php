@php
    $form_sections = $application_type->formSections->sortBy('order_column');
    $form_inputs = $application->formInputs;
    $form_fields = $application_type->formFields->sortBy('order_column');
@endphp

@if($form_fields->count() == 0)
    <div class="card">
        <div class="card-body text-center text-muted">
            <h4 class="text-accent">{{ __('There are no fields in this application to fill.') }}</h4>
            <small class="text-muted">{{ __('Hit continue and head on over to uploading documents.') }}</small>
        </div>
    </div>
@else
    {!! $application_type->render($applicant, $form_inputs) !!}
@endif
