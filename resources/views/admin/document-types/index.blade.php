@extends('paperless::admin.document-types.document-types')

@section('page-title')
    {{ if_route('admin.document-types.trash') ? __('Deleted Document Types') : __('Document Types') }}
@endsection

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('content')
    @if($document_types->isNotEmpty() || config('paperless.models.document_type')::query()->exists())
        <div class="card">
            <x-forms::form
                :action="route(if_route('admin.document-types.trash') ? 'admin.document-types.trash' : 'admin.document-types.index')"
                :model="request()->query()"
                id="filter"
                method="GET"
            >
                @include('paperless::admin.document-types._filter')
            </x-forms::form>

            @include('paperless::admin.document-types._table', [
                'no_bulk' => $trashed,
                'no_checkbox' => $trashed,
            ])
        </div>
    @else
        <x-forms::no-items
            icon="zmdi zmdi-file"
            :create-action="route('admin.document-types.create')"
            :model_type="__('document types')"
            :model="config('paperless.models.document_type')"
        />
    @endif
@endsection
