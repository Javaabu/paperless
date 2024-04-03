@extends('paperless::admin.document-types.document-types')

@section('page-title', __('Edit Document Type'))

@section('content')
    <x-forms::form method="PATCH" :model="$document_type" :action="route('admin.document-types.update', $document_type)">
    @include('paperless::admin.document-types._form')
    </x-forms::form>
@endsection
