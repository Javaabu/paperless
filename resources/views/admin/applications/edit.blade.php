@extends('paperless::admin.applications.applications')

@section('inner-content')
    <x-forms::form method="PATCH" :model="$application" :action="route('admin.applications.update', $application)">
        @include('paperless::admin.applications._form')

        <x-forms::button-group :inline="true" class="d-flex justify-content-between mt-0">
            <x-forms::submit name="action" value="cancel" color="dark" class="btn--icon-text btn--raised">
                <i class="zmdi zmdi-block"></i> {{ __('Cancel Application') }}
            </x-forms::submit>
            <x-forms::submit color="success" class="btn--icon-text btn--raised">
                <i class="zmdi zmdi-check"></i> {{ __('Save & Continue') }}
            </x-forms::submit>
        </x-forms::button-group>
    </x-forms::form>
@endsection
