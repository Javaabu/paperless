<x-forms::card>

    @php
        $document_types = config('paperless.models.document_type')::pluck('name', 'id');
    @endphp
    <x-forms::select2 name="document_type" :options="$document_types" :required="true" :inline="true" />
    <x-forms::checkbox name="is_required" :label="__('Is Required')" :inline="true" />


    <x-forms::button-group :inline="true">
        <x-forms::submit color="success" class="btn--icon-text btn--raised">
            <i class="zmdi zmdi-check"></i> {{ __('Save') }}
        </x-forms::submit>

        <x-forms::link-button color="light" class="btn--icon-text" :url="route('admin.application-types.document-types.index', $application_type)">
            <i class="zmdi zmdi-close-circle"></i> {{ __('Cancel') }}
        </x-forms::link-button>
    </x-forms::button-group>

</x-forms::card>
