@extends('admin.services.services')

@section('page-title', __('New Service'))

@section('content')
    {!! Form::open(['route' => 'admin.services.store']) !!}
    @include('admin.services._form')
    {!! Form::close() !!}
@endsection
