@extends('paperless::admin.services.services')

@section('page-title')
    {{ if_route('admin.services.trash') ? __('Deleted Services') : __('Services') }}
@endsection

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('content')
    @if($services->isNotEmpty() || config('paperless.models.service')::exists())
        <div class="card">
            {!! Form::open(['route' => if_route('admin.services.trash') ? 'admin.services.trash' : 'admin.services.index', 'id' => 'filter', 'method' => 'GET']) !!}
            @include('paperless::admin.services._filter')
            {!! Form::close() !!}

            @include('paperless::admin.services._table', [
                'no_bulk' => $trashed,
                'no_checkbox' => $trashed,
            ])
        </div>
    @else
        <x-forms::no-items
            icon="zmdi zmdi-widgets"
            :create-action="route('admin.services.create')"
            :model_type="__('services')"
            :model="config('paperless.models.service')"
        />
    @endif
@endsection
