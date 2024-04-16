@extends('admin.applications.applications')

@section('page-title')
    {{ if_route('admin.applications.trash') ? __('Deleted Applications') : __('Applications') }}
@endsection

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('content')
    @php
        $application_class = config('paperless.application_model');
    @endphp

    <div class="row">
        @foreach ($charts as $chart)
            <div class="col-md-4">
                <x-admin.count-stats :chart="$chart"/>
            </div>
        @endforeach
    </div>

    @if($applications->isNotEmpty() || $application_class::exists())
        <div class="card">
            {!! Form::open(['route' => if_route('admin.applications.trash') ? 'admin.applications.trash' : 'admin.applications.index', 'id' => 'filter', 'method' => 'GET']) !!}
            @include('admin.applications._filter')
            {!! Form::close() !!}

            @include('admin.applications._table', [
                'no_bulk' => $trashed,
                'no_checkbox' => $trashed,
            ])
        </div>
    @else
        @include('admin.components.no-items', [
            'icon' => 'zmdi zmdi-file',
            'create_action' => route('admin.applications.create'),
            'model_type' => __('applications'),
            'model' => $application_class,
        ])
    @endif
@endsection
