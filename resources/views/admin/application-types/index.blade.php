@extends('paperless::admin.application-types.application-types')

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('content')
    @if($application_types->isNotEmpty() || config('paperless.models.application_type')::exists())
        <div class="card">
            <x-forms::form
                :action="route('admin.application-types.index')"
                :model="request()->query()"
                id="filter"
                method="GET"
            >
            @include('paperless::admin.application-types._filter')
            </x-forms::form>

            @include('paperless::admin.application-types._table')
        </div>
    @else
        <div class="no-items">
            <div class="card-body">
                <i class="zmdi zmdi-file main-icon mb-4"></i>
                <p class="lead mb-4">
                    {{ __('No application types available.') }}
                </p>
            </div>
        </div>
    @endif
@endsection
