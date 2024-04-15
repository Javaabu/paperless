@extends('paperless::admin.application-types.document-types.document-types')

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('inner-content')

    <x-forms::form :action="route('admin.application-types.document-types.store', $application_type)">
        @include('paperless::admin.application-types.document-types._form')
    </x-forms::form>

    @if($document_types->isNotEmpty() || $application_type->documentTypes()->exists())
        <div class="card mt-4">
            <x-forms::form
                :action="route('admin.application-types.document-types.index', $application_type)"
                :model="request()->query()"
                id="filter"
                method="GET"
            >
                @include('paperless::admin.application-types.document-types._filter')
            </x-forms::form>

            @include('paperless::admin.application-types.document-types._table')
        </div>
    @else
        <div class="no-items">
            <div class="card-body">
                <i class="zmdi zmdi-file main-icon mb-4"></i>
                <p class="lead mb-4">
                    {{ __('No document types applied.') }}
                </p>
            </div>
        </div>
    @endif

@endsection
