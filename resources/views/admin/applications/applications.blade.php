@extends('layouts.admin')

@if(! isset($application))
    @section('title', 'Applications')
    @section('page-title', __('Applications'))
@endif

@section('top-search')
    @include('admin.partials.search-model', [
        'search_route' => 'admin.applications.index',
        'search_placeholder' => __('Search for applications...'),
    ])
@endsection

@section('model-actions')
    @include('admin.applications._actions')
@endsection

@php
    $edit_routes = [
        'admin.applications.create',
        'admin.applications.edit',
        'admin.applications.documents',
        'admin.applications.review',
    ];

    $show_routes = [
        'admin.applications.show',
        'admin.applications.view-documents',
        'admin.applications.details',
        'admin.applications.history',
    ];

@endphp
@if(request()->routeIs($edit_routes))
    @section('page-title', __('Edit Application - :application_type', ['application_type' => $application->applicationType?->name]))
    @if ($application->id)
        @section('page-subheading', $application->formatted_id)
    @endif

    @section('content')
        @include('admin.applications._tabs')

        @yield('inner-content', '')
    @endsection
@endif

@if (request()->routeIs($show_routes))
    @section('page-title', __('View Application - :application_type', ['application_type' => $application->applicationType?->name]))
    @section('page-subheading', $application->name)

    @section('content')
        @include('admin.applications.show._show-tabs')

        @yield('inner-content')
    @endsection
@endif
