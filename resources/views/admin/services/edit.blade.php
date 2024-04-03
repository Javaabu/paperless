@extends('paperless::admin.services.services')

@section('page-title', __('Edit Service'))

@section('content')

    <x-forms::form method="PATCH" :model="$service" :action="route('admin.services.update', $service)">
    @include('paperless::admin.services._form')
    </x-forms::form>
@endsection
