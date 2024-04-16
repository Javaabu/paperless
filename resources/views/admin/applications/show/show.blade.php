@extends('admin.applications.applications')

@section('inner-content')

    @include('admin.applications.show.cards._summary')

    @can('assignUser', $application)
    @include('admin.applications.show.cards._assign-user')
    @endcan

    @include('admin.applications.show.cards._admin-section')
    @include('admin.applications.show.cards._actions')
    @include('admin.applications.show.cards._payments')
    @include('admin.applications.show.cards._process-details')

@endsection
