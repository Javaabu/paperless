@extends('admin.applications.applications')

@section('inner-content')
   @include('admin.applications.documents._documents')
   @include('admin.applications.documents._additional-documents')

    <x-admin.application-form-buttons :application="$application">
        <x-admin.link-button type="success" :url="route('admin.applications.review', $application)">
            <span class="mr-2">{{ __( 'Save & Continue') }}</span>
            <i class="fa-regular fa-arrow-right"></i>
        </x-admin.link-button>
    </x-admin.application-form-buttons>
@endsection
