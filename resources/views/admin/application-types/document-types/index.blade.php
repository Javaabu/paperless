@extends('admin.application-types.document-types.document-types')

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('inner-content')

    {!! Form::open(['url' => route('admin.application-types.document-types.store', $application_type)]) !!}
    @include('admin.application-types.document-types._form')
    {!! Form::close() !!}

    @if($document_types->isNotEmpty() || $application_type->documentTypes()->exists())
        <div class="card mt-4">
            {!! Form::open(['url' => route('admin.application-types.document-types.index', $application_type), 'id' => 'filter', 'method' => 'GET']) !!}
            @include('admin.application-types.document-types._filter')
            {!! Form::close() !!}

            @include('admin.application-types.document-types._table')
        </div>
    @else
        @include('admin.components.no-items', [
            'icon' => 'zmdi zmdi-file',
            'model_type' => __('document types'),
            'can_create' => false,
        ])
    @endif

@endsection
