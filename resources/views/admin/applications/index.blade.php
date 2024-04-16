@extends('paperless::admin.applications.applications')

@section('page-title')
    {{ if_route('admin.applications.trash') ? __('Deleted Applications') : __('Applications') }}
@endsection

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('content')
    @php
        $application_class = config('paperless.models.application');
    @endphp

    @if($applications->isNotEmpty() || $application_class::exists())
        <div class="card">
            {!! Form::open(['route' => if_route('admin.applications.trash') ? 'admin.applications.trash' : 'admin.applications.index', 'id' => 'filter', 'method' => 'GET']) !!}
            @include('paperless::admin.applications._filter')
            {!! Form::close() !!}

            @include('paperless::admin.applications._table', [
                'no_bulk' => $trashed,
                'no_checkbox' => $trashed,
            ])
        </div>
    @else
        <x-forms::no-items
            icon="zmdi zmdi-widgets"
            :create-action="route('admin.applications.create')"
            :model_type="__('applications')"
            :model="config('paperless.models.application')"
        />
    @endif
@endsection
