@extends('admin.services.services')

@section('page-title', __('Edit Service'))

@section('content')
    {!! Form::model($service, ['method' => 'PATCH', 'route' => ['admin.services.update', $service]]) !!}
    @include('admin.services._form')
    {!! Form::close() !!}
@endsection
