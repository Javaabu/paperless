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
                @foreach ($available_process_actions as $action)
                    @php
                        $action_class = config('paperless.application_status')::make($action, $application);
                    @endphp
                    <x-forms::submit data-confirm="Make sure you are taking the right action!"
                                     class="btn--icon-text btn--raised"
                                     name="action"
                                     value="{{ $action_class->getValue() }}"
                                     color="{{ $action_class->getColor() }}"
                    >
                        {{ $action_class->getActionLabel() }}
                    </x-forms::submit>
                @endforeach
            </div>
        </x-forms::form>
    </div>
</div>
@endif
