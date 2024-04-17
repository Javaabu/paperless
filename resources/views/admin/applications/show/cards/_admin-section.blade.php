@php
    $admin_sections = $application->applicationType->formSections()->where('is_admin_section', true)->get();
    $form_inputs = $application->formInputs;
@endphp

@foreach ($admin_sections as $section)
    @php
        $field_ids = $section->formFields->pluck('id');
        $section_form_inputs = $form_inputs->whereIn('form_field_id', $field_ids);
    @endphp

    @if (! $application->status->isLocked())
        @include('paperless::admin.applications.show.cards._form-section', [
            'section' => $section,
            'section_form_inputs' => $section_form_inputs
        ])
    @endif

    @if ($application->status->isLocked())
        @include('paperless::admin.applications.show.cards._form-section-view', [
            'section' => $section,
            'section_form_inputs' => $section_form_inputs
        ])
    @endif
@endforeach
