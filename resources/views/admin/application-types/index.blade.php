@extends('paperless::admin.application-types.application-types')

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('content')
    @if($application_types->isNotEmpty() || config('paperless.models.application_type')::exists())
        <div class="card">
            {!! Form::open(['route' => 'admin.application-types.index', 'id' => 'filter', 'method' => 'GET']) !!}
            @include('paperless::admin.application-types._filter')
            {!! Form::close() !!}

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
