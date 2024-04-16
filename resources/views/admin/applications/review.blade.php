@extends('paperless::admin.applications.applications')

@section('inner-content')
{{--    <x-admin.alert icon="circle-info"--}}
{{--                   color="info"--}}
{{--                   :title="__('Review your information')">--}}
{{--        <ul>--}}
{{--            <li>{{ __('Please review your information before submitting your application.') }}</li>--}}
{{--            <li>{{ __('If you need to make any changes, please click the "Previous" button below.') }}</li>--}}
{{--            <li>{{ __('If you are ready to submit your application, please click the "Confirm & Submit" button below.') }}</li>--}}
{{--        </ul>--}}
{{--    </x-admin.alert>--}}

    @if (session('alerts'))
        @php
            $alerts = session('alerts');
        @endphp
        @foreach ($alerts as $alert)
{{--        <x-admin.alert :color="$alert['type']"--}}
{{--                       :title="$alert['title']">--}}
{{--            {{ $alert['text'] }}--}}
{{--        </x-admin.alert>--}}
        @endforeach
    @endif

    {!! $application->renderInfoList() !!}
    {!! $application->renderRequiredDocumentList() !!}
    {!! $application->renderAdditionalDocumentList() !!}

{{--    <x-admin.application-form-buttons :application="$application" :previous-url="route('admin.applications.documents', $application)">--}}
{{--        {!! Form::model($application, ['method' => 'PATCH', 'route' => ['admin.applications.status-update', $application]]) !!}--}}
{{--        <x-admin.input-button type="submit" name="action" value="{{ $application->status == \App\Application\Enums\ApplicationStatuses::Incomplete ? 'resubmit' : 'submit' }}" icon="check" color="success">--}}
{{--            <span class="ml-2">{{ __('Confirm & Submit') }}</span>--}}
{{--        </x-admin.input-button>--}}
{{--        {!! Form::close() !!}--}}
{{--    </x-admin.application-form-buttons>--}}
@endsection
