@extends('admin.application-types.services.services')

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('inner-content')

    {!! Form::open(['url' => route('admin.application-types.services.store', $application_type)]) !!}
    @include('admin.application-types.services._form')
    {!! Form::close() !!}

    @if($services->isNotEmpty() || $application_type->documentTypes()->exists())
        <div class="card mt-4">
            {!! Form::open(['url' => route('admin.application-types.services.index', $application_type), 'id' => 'filter', 'method' => 'GET']) !!}
            @include('admin.application-types.services._filter')
            {!! Form::close() !!}

            @include('admin.application-types.services._table')
        </div>
    @else
        @include('admin.components.no-items', [
            'icon' => 'zmdi zmdi-file',
            'model_type' => __('services'),
            'can_create' => false,
        ])
    @endif

@endsection
