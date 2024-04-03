@extends('admin.application-types.users.users')

@section('page-subheading')
    <small>{{ $title }}</small>
@endsection

@section('inner-content')

    {!! Form::open(['url' => route('admin.application-types.assigned-users.store', $application_type)]) !!}
    @include('admin.application-types.users._form')
    {!! Form::close() !!}

    @if($users->isNotEmpty() || $application_type->assignedUsers()->exists())
        <div class="card mt-4">
            {!! Form::open(['url' => route('admin.application-types.assigned-users.index', $application_type), 'id' => 'filter', 'method' => 'GET']) !!}
            @include('admin.application-types.users._filter')
            {!! Form::close() !!}

            @include('admin.application-types.users._table')
        </div>
    @else
        @include('admin.components.no-items', [
            'icon' => 'zmdi zmdi-file',
            'model_type' => __('assigned_user'),
            'can_create' => false,
        ])
    @endif

@endsection
