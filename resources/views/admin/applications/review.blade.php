@extends('paperless::admin.applications.applications')

@section('inner-content')
    <x-paperless::alert icon="circle-info"
                   color="info"
                   :title="__('Review your information')">
        <ul>
            <li>{{ __('Please review your information before submitting your application.') }}</li>
            <li>{{ __('If you need to make any changes, please click the "Previous" button below.') }}</li>
            <li>{{ __('If you are ready to submit your application, please click the "Confirm & Submit" button below.') }}</li>
        </ul>
    </x-paperless::alert>

    @if (session('alerts'))
        @php
            $alerts = session('alerts');
        @endphp
        @foreach ($alerts as $alert)
        <x-paperless::alert :color="$alert['type']"
                       :title="$alert['title']">
            {{ $alert['text'] }}
        </x-paperless::alert>
        @endforeach
    @endif

    {!! $application->renderInfoList() !!}
    {!! $application->renderRequiredDocumentList() !!}
    {!! $application->renderAdditionalDocumentList() !!}

    <x-forms::button-group :inline="true" class="d-flex justify-content-between mt-0">
        <x-forms::form method="PATCH" :model="$application" :action="route('admin.applications.status-update', $application)">
            <x-forms::submit color="dark" class="btn--icon-text btn--raised">
                <i class="zmdi zmdi-block"></i> {{ __('Cancel Application') }}
            </x-forms::submit>
        </x-forms::form>

        <div class="d-flex">
            <x-forms::link-button color="dark" class="btn--icon-text btn--raised" :url="route('admin.applications.documents', $application)">
                <i class="zmdi zmdi-arrow-left"></i> {{ __('Previous') }}
            </x-forms::link-button>
            <x-forms::form method="PATCH" :model="$application" :action="route('admin.applications.update', $application)">
                <x-forms::submit color="success" class="btn--icon-text btn--raised">
                    <i class="zmdi zmdi-check"></i> {{ __('Confirm & Submit') }}
                </x-forms::submit>
            </x-forms::form>
        </div>
    </x-forms::button-group>
@endsection
