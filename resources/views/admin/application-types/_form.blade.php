<x-forms::card>
    @php
        $entity_types = config('paperless.models.entity_type')::all()->pluck('name', 'id');
    @endphp
    <x-forms::text name="name" maxlength="255" :disabled="true" :inline="true" />
    <x-forms::text name="code" maxlength="255" :disabled="true" :inline="true" />
    <x-forms::text name="application_category" maxlength="255" :disabled="true" :inline="true" />
    <x-forms::select name="entity_types" :options="$entity_types" :disabled="true" :multiple="true" :inline="true" />
    <x-forms::text name="eta_duration" maxlength="255" :required="true" :inline="true" />
    <x-forms::textarea name="description" maxlength="255" :required="false" :inline="true" />

    <x-forms::button-group :inline="true">
        <x-forms::submit color="success" class="btn--icon-text btn--raised">
            <i class="zmdi zmdi-check"></i> {{ __('Save') }}
        </x-forms::submit>

        <x-forms::link-button color="light" class="btn--icon-text" :url="route('admin.application-types.index')">
            <i class="zmdi zmdi-close-circle"></i> {{ __('Cancel') }}
        </x-forms::link-button>
    </x-forms::button-group>
</x-forms::card>
