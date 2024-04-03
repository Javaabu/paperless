@extends('paperless::admin.services.services')

@section('page-title', __('New Service'))

@section('content')
    <x-forms::form :action="route('admin.services.store')">
    @include('paperless::admin.services._form')
    </x-forms::form>
@endsection
