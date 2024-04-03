<x-admin.form-container with-floating-submit>

    <x-admin.input-group for="name" :label="__('Name')">
        <x-admin.input-text name="name" disabled :placeholder="__('Name')" :value="$application_type->name" />
    </x-admin.input-group>

    <x-admin.input-group for="code" :label="__('Code')">
        <x-admin.input-text name="code" disabled :placeholder="__('Code')" :value="$application_type->code" />
    </x-admin.input-group>

    <x-admin.input-group for="application_category" :label="__('Category')">
        <x-admin.input-text name="application_category" disabled :placeholder="__('Category')" :value="$application_type->application_category->getLabel()" />
    </x-admin.input-group>

    <x-admin.input-group for="entity_types" :label="__('Entity Types')">
        @php
            $entity_types = \App\Models\EntityType::all()->pluck('name', 'id');
        @endphp
        <x-admin.select name="entity_types[]"
                        multiple
                        disabled
                        :options="$entity_types"
                        :value="old('entity_types', $application_type->entityTypes?->pluck('id')->toArray())"
        />
    </x-admin.input-group>

    <x-admin.input-group for="eta_duration" :label="__('ETA Duration')" required>
        <x-admin.input-number append="days" name="eta_duration" required :placeholder="__('ETA Duration')" :value="old('eta_duration', $application_type->eta_duration)" />
    </x-admin.input-group>

    <x-admin.input-group for="alert_duration" :label="__('Alert Duration')" required>
        <x-admin.input-number append="days" name="alert_duration" required :placeholder="__('Alert Duration')" :value="old('alert_duration', $application_type->alert_duration)" />
    </x-admin.input-group>

    <x-admin.input-group for="description" :label="__('Description')">
        @php
            $upload_url = isset($application_type->id) ? route('admin.application-types.upload', $application_type) : '#';
        @endphp
        <x-admin.editor name="description" :upload-url="$upload_url" />
    </x-admin.input-group>

    <x-slot:buttons>
        <x-admin.form-save-buttons :cancel-url="route('admin.application-types.index')" />
    </x-slot:buttons>

</x-admin.form-container>
