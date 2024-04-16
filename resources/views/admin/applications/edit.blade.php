@extends('admin.applications.applications')

@section('inner-content')
    {!! Form::model($application, ['method' => 'PATCH', 'route' => ['admin.applications.update', $application]]) !!}
    @include('admin.applications._form')

    <x-admin.application-form-buttons :application="$application">
        <x-admin.input-button type="submit" name="action" value="submit" icon="check" color="success">
            <span class="ml-2">{{ __('Save & Continue') }}</span>
        </x-admin.input-button>
    </x-admin.application-form-buttons>

    {!! Form::close() !!}
@endsection
