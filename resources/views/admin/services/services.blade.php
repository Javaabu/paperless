@extends('layouts.admin')

@section('title', 'Services')
@section('page-title', __('Services'))

@section('top-search')
    @include('admin.partials.search-model', [
        'search_route' => 'admin.services.index',
        'search_placeholder' => __('Search for services...'),
    ])
@endsection

@section('model-actions')
    @include('admin.services._actions')
@endsection
