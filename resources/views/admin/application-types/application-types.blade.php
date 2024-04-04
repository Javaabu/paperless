@extends(config('paperless.views_layout'))
@if(! isset($application_type))
    @section('title', 'Application Types')
    @section('page-title', __('Application Types'))
@endif

@section('model-actions')
    @include('paperless::admin.application-types._actions')
@endsection

@if(isset($application_type))

    @section('page-title', __('Edit Application Type'))
    @section('page-subheading', $application_type->name)

    @section('content')
        @include('paperless::admin.application-types._tabs')

        @yield('inner-content')
    @endsection
@endif
