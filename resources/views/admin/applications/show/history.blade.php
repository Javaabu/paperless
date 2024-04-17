@extends('paperless::admin.applications.applications')

@section('inner-content')

    <div class="card">
        @include('paperless::admin.status-events.history', ['model' => $application])
    </div>

@endsection
