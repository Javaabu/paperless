@extends('paperless::admin.applications.applications')

@section('inner-content')

    @include('paperless::admin.applications.show.cards._summary')

    @include('paperless::admin.applications.show.cards._admin-section')
    @include('paperless::admin.applications.show.cards._actions')

    @if (config('paperless.relations.services'))
        @include('paperless::admin.applications.show.cards._payments')
    @endif

@endsection
