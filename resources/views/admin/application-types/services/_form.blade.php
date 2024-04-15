<x-forms::card>

    @php
        $services = config('paperless.models.service')::pluck('name', 'id');
    @endphp
    <x-forms::select name="service" :options="$services" :required="true" :inline="true" />
    <x-forms::checkbox name="is_applied_automatically" :label="__('Is Applied Automatically')" :inline="true" />

    <x-forms::button-group :inline="true">
        <x-forms::submit color="success" class="btn--icon-text btn--raised">
            <i class="zmdi zmdi-check"></i> {{ __('Save') }}
        </x-forms::submit>

        <x-forms::link-button color="light" class="btn--icon-text" :url="route('admin.application-types.services.index', $application_type)">
            <i class="zmdi zmdi-close-circle"></i> {{ __('Cancel') }}
        </x-forms::link-button>
    </x-forms::button-group>

</x-forms::card>
