@extends('paperless::admin.applications.applications')

@section('page-title', __('New Application'))

@section('content')
    <x-forms::form :action="route('admin.applications.create')" method="GET">
        @include('paperless::admin.applications._initialize')
    </x-forms::form>
@endsection
