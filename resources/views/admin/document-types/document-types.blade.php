@extends(config('paperless.views_layout'))

@section('title', 'Document Types')
@section('page-title', __('Document Types'))

@section('model-actions')
    @include('paperless::admin.document-types._actions')
@endsection
