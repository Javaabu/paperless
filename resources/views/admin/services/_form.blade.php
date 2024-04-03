<x-forms::card>

    <x-forms::text name="name" maxlength="255" :required="true" :inline="true" />
    <x-forms::text name="code" maxlength="255" :required="true" :inline="true" />
    <x-forms::number name="fee" :min="0"  :required="true" :inline="true" />

    <x-forms::button-group :inline="true">
        <x-forms::submit color="success" class="btn--icon-text btn--raised">
            <i class="zmdi zmdi-check"></i> {{ __('Save') }}
        </x-forms::submit>

        <x-forms::link-button color="light" class="btn--icon-text" :url="route('admin.services.index')">
            <i class="zmdi zmdi-close-circle"></i> {{ __('Cancel') }}
        </x-forms::link-button>
    </x-forms::button-group>
</x-forms::card>
