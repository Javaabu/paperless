@php
    $process_actions = config('paperless.enums.application_status')::getProcessActions();
    $available_process_actions = $application->status->transitionableStates();
@endphp

@if ($available_process_actions)
<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Application Processing') }}</h4>
    </div>
    <div class="card-body">
        <x-forms::form method="PATCH" :model="$application" :action="route('admin.applications.status-update', $application)">
            <x-forms::text name="remarks" placeholder="{{ __('Add any specific messages you want to record with the change...') }}" />

            <div class="mt-4">
                @foreach ($available_process_actions as $action => $color)
                    <x-forms::submit data-confirm="Make sure you are taking the right action!"
                                     class="btn--icon-text btn--raised"
                                     name="action"
                                     value="{{ $action }}"
                                     color="{{ $color }}"
                    >
                        {{ str($action)->snake()->replace('_', ' ')->title()->__toString() }}
                    </x-forms::submit>
                @endforeach
            </div>
        </x-forms::form>
    </div>
</div>
@endif
