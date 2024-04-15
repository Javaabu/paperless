@extends('paperless::admin.application-types.application-types')

@section('page-title', __('Edit Application Type'))

@section('content')
    @parent

    <x-forms::form method="PATCH" :model="$application_type" :action="route('admin.application-types.update', $application_type)">
        @include('paperless::admin.application-types._form')
    </x-forms::form>
@endsection
