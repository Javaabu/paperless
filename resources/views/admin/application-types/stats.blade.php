@extends('admin.application-types.application-types')

@section('inner-content')
    @include('admin.stats._generator', ['url' => route('admin.application-types.stats.export', $application_type), 'filters' => $filters])
@endsection
