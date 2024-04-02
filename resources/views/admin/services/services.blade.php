@extends('layouts.admin')

@section('title', 'Services')
@section('page-title', __('Services'))

@section('model-actions')
    @include('paperless::admin.services._actions')
@endsection
