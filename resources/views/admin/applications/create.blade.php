@extends('paperless::admin.applications.applications')

@section('page-title', __('New Application - :type', ['type' => $application_type->name]))

@section('inner-content')

    <x-forms::form :action="route('admin.applications.store')">
        <x-forms::text :show-label="false" name="applicant_type_id" value="{{ request()->input('applicant_type') }}" :hidden="true" :required="true" />
        <x-forms::text :show-label="false" name="applicant_id" value="{{ $applicant->id }}" :hidden="true" :required="true" />
        <x-forms::text :show-label="false" name="application_type_id" value="{{ $application_type->id }}" :hidden="true" :required="true" />

        @include('paperless::admin.applications._form')

        <x-forms::button-group :inline="true">
            <x-forms::submit color="success" class="btn--icon-text btn--raised">
                <i class="zmdi zmdi-check"></i> {{ __('Continue') }}
            </x-forms::submit>

            <x-forms::link-button color="light" class="btn--icon-text" :url="route('admin.applications.index')">
                <i class="zmdi zmdi-close-circle"></i> {{ __('Cancel') }}
            </x-forms::link-button>
        </x-forms::button-group>
    </x-forms::form>

@endsection

