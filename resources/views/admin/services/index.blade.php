@extends('admin.services.services')

@section('page-title')
    {{ if_route('admin.services.trash') ? __('Deleted Services') : __('Services') }}
@endsection

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('content')
    @if($services->isNotEmpty() || App\Models\Service::exists())
        <div class="card">
            {!! Form::open(['route' => if_route('admin.services.trash') ? 'admin.services.trash' : 'admin.services.index', 'id' => 'filter', 'method' => 'GET']) !!}
            @include('admin.services._filter')
            {!! Form::close() !!}

            @include('admin.services._table', [
                'no_bulk' => $trashed,
                'no_checkbox' => $trashed,
            ])
        </div>
    @else
        @include('admin.components.no-items', [
            'icon' => 'zmdi zmdi-file',
            'create_action' => route('admin.services.create'),
            'model_type' => __('services'),
            'model' => App\Models\Service::class,
        ])
    @endif
@endsection
