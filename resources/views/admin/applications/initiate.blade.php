@extends('admin.applications.applications')

@section('page-title', __('New Application'))

@section('content')
    {!! Form::open(['route' => 'admin.applications.create', 'method' => 'GET']) !!}
    @include('admin.applications._initialize')
    {!! Form::close() !!}
@endsection
