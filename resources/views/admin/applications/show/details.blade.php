@extends('admin.applications.applications')

@section('inner-content')

    {!! $application->renderInfoList(true) !!}

@endsection
