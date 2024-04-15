@extends('paperless::admin.application-types.document-types.document-types')

@section('page-subheading')
    <small>{{ __("Form Fields For :application_type", ['application_type' => $application_type->name]) }}</small>
@endsection

@section('inner-content')

    @forelse ($form_sections as $form_section)
        @php
            $form_section_order = $loop->index + 1;
            $form_section_order = str_pad($form_section_order, 2, '0', STR_PAD_LEFT);
            $form_fields = $form_section->formFields->where('field_group_id', null)->sortBy('order_column');
        @endphp
        <div class="card mb-4">
            <div class="card-body" style="background-color: #868e96;color:white;padding:15px;">
                <div>{{ __("{$form_section_order}. {$form_section->name}") }}</div>
            </div>
        </div>
        <div class="ml-5">
            @forelse ($form_fields as $form_field)
                @include('paperless::admin.form-fields._field-card', ['form_field' => $form_field])
            @empty
                @include('paperless::admin.form-fields._empty-field')
            @endforelse

            @php
                $field_groups = $form_section->fieldGroups?->sortBy('order_column');
            @endphp
            @foreach($field_groups as $field_group)
                @php
                    $field_group_order = $loop->index + 1;
                    $field_group_order = str_pad($field_group_order, 2, '0', STR_PAD_LEFT);
                    $form_fields = $field_group->formFields?->sortBy('order_column');
                @endphp
                <div class="card mb-4">
                    <div class="card-body" style="background-color: #868e96;color:white;padding:15px;">
                        <div>{{ __("{$field_group_order}. Group: {$field_group->name}") }}</div>
                    </div>
                </div>
                <div class="ml-5">
                    @forelse ($form_fields as $form_field)
                        @include('paperless::admin.form-fields._field-card', ['form_field' => $form_field])
                    @empty
                        @include('paperless::admin.form-fields._empty-field')
                    @endforelse
                </div>
            @endforeach
        </div>

    @empty
        <div class="no-items">
            <div class="card-body">
                <i class="zmdi zmdi-file main-icon mb-4"></i>
                <p class="lead mb-4">
                    {{ __('No form fields were found for this application type.') }}
                </p>
            </div>
        </div>
    @endforelse
@endsection
