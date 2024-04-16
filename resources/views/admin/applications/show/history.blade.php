@extends('admin.applications.applications')

@section('inner-content')

    <div class="card">
        @include('admin.status-events.history', ['model' => $application])
    </div>

@endsection
