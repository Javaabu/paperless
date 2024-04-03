<x-forms::card>
    <x-forms::text name="name" maxlength="255" required inline />
    <x-forms::text name="slug" maxlength="255" required inline />
    <x-forms::textarea name="description" required inline />

    <x-forms::button-group :inline="true">
        <x-forms::submit color="success" class="btn--icon-text btn--raised">
            <i class="zmdi zmdi-check"></i> {{ __('Save') }}
        </x-forms::submit>

        <x-forms::link-button color="light" class="btn--icon-text" :url="route('admin.document-types.index')">
            <i class="zmdi zmdi-close-circle"></i> {{ __('Cancel') }}
        </x-forms::link-button>
    </x-forms::button-group>
</x-forms::card>
