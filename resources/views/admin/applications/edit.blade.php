@extends('paperless::admin.applications.applications')

@section('inner-content')

    <x-forms::form method="PATCH" :model="$application" :action="route('admin.applications.update', $application)">
        @include('paperless::admin.applications._form')


        <x-forms::button-group :inline="true">
            <x-forms::submit color="success" class="btn--icon-text btn--raised">
                <i class="zmdi zmdi-check"></i> {{ __('Save & Continue') }}
            </x-forms::submit>

            <x-forms::link-button color="light" class="btn--icon-text" :url="route('admin.applications.index')">
                <i class="zmdi zmdi-close-circle"></i> {{ __('Cancel') }}
            </x-forms::link-button>
        </x-forms::button-group>

    </x-forms::form>
@endsection
