@extends(config('paperless.views_layout'))

@section('title', 'Services')
@section('page-title', __('Services'))

@section('model-actions')
    @include('paperless::admin.services._actions')
@endsection
