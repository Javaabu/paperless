@extends('admin.applications.applications')

@section('page-title', __('New Application - :type', ['type' => $application_type->name]))

@section('inner-content')

    @ray($errors)
    {!! Form::open(['route' => 'admin.applications.store']) !!}
        @include('admin.applications._form')
        <x-admin.application-form-buttons :application="$application">
            {!! Form::model(['route' => ['admin.applications.store']]) !!}
            <x-admin.input-button type="submit" name="action" value="submit" icon="check" color="success">
                <span class="ml-2">{{ __('Save & Continue') }}</span>
            </x-admin.input-button>
            {!! Form::close() !!}
        </x-admin.application-form-buttons>
    {!! Form::close() !!}
@endsection

