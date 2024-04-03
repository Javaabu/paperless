@extends('admin.application-types.application-types')

@section('top-search')
    @include('admin.partials.search-model', [
        'search_url' => route('admin.application-types.assigned-users.index', $application_type),
        'search_placeholder' => __('Search for assigned users...'),
    ])
@endsection
