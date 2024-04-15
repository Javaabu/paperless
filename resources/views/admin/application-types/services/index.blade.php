@extends('paperless::admin.application-types.services.services')

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('inner-content')

    <x-forms::form :action="route('admin.application-types.services.store', $application_type)">
        @include('paperless::admin.application-types.services._form')
    </x-forms::form>


    @if($services->isNotEmpty() || $application_type->documentTypes()->exists())
        <div class="card mt-4">
            <x-forms::form
                :action="route('admin.application-types.services.index', $application_type)"
                :model="request()->query()"
                id="filter"
                method="GET"
            >
            @include('paperless::admin.application-types.services._filter')
            </x-forms::form>

            @include('paperless::admin.application-types.services._table')
        </div>
    @else
        <div class="no-items">
            <div class="card-body">
                <i class="zmdi zmdi-file main-icon mb-4"></i>
                <p class="lead mb-4">
                    {{ __('No services applied.') }}
                </p>
            </div>
        </div>
    @endif

@endsection
