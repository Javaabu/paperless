@php
    $process_actions = \App\Application\Enums\ApplicationStatuses::getProcessActions();
    $available_process_actions = collect($process_actions)->filter(function ($color, $action) use ($application) {
        return auth()->user()->can($action, $application);
    })->toArray();
@endphp

@if ($available_process_actions)
<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Application Processing') }}</h4>
    </div>
    <div class="card-body">
        {!! Form::model($application, ['method' => 'PATCH', 'route' => ['admin.applications.status-update', $application]]) !!}
        <x-admin.input-group label="Remarks" for="remarks" stacked>
            <x-admin.input-text name="remarks" placeholder="{{ __('Add any specific messages you want to record with the change...') }}" />
        </x-admin.input-group>

        <div class="mt-4">
            @foreach ($available_process_actions as $action => $color)
                <x-admin.input-button data-confirm="Make sure you are taking the right action!" type="submit" name="action" value="{{ $action }}" color="{{ $color }}">
                    {{ str($action)->snake()->replace('_', ' ')->title()->__toString() }}
                </x-admin.input-button>
            @endforeach
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endif
