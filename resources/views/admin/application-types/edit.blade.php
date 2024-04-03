@extends('admin.application-types.application-types')

@section('page-title', __('Edit Application Type'))

@section('content')
    @parent

    {!! Form::model($application_type, ['method' => 'PATCH', 'route' => ['admin.application-types.update', $application_type]]) !!}
    @include('admin.application-types._form')
    {!! Form::close() !!}
@endsection
