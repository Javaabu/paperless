@extends('paperless::admin.applications.applications')

@section('inner-content')

    @include('paperless::admin.applications.show.cards._summary')

    @include('paperless::admin.applications.show.cards._admin-section')
    @include('paperless::admin.applications.show.cards._actions')

    @if (config('paperless.relations.services'))
        @include('paperless::admin.applications.show.cards._payments')
    @endif

    @php
        $blade_files_to_render = $application->applicationType->getApplicationTypeClassInstance()->getExtraBladeViewsToRender('summary');
    @endphp

    @foreach($blade_files_to_render as $blade_file)
        @include($blade_file, ['application' => $application])
    @endforeach

{{--    @dd($application->applicationType->getApplicationTypeClassInstance()->getName())--}}



@endsection
