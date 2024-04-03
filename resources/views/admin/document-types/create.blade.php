@extends('paperless::admin.document-types.document-types')

@section('page-title', __('New Document Type'))

@section('content')
    <x-forms::form :action="route('admin.document-types.store')">
    @include('paperless::admin.document-types._form')
    </x-forms::form>
@endsection
