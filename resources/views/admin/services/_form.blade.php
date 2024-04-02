<x-admin.form-container with-floating-submit>

    <x-admin.input-group for="name" :label="__('Name')" required>
        <x-admin.input-text name="name" required :placeholder="__('Name')" :value="old('name', $service->name)" />
    </x-admin.input-group>

    <x-admin.input-group for="code" :label="__('Code')" required>
        <x-admin.input-text name="code" required :placeholder="__('Code')" :value="old('code', $service->code)" />
    </x-admin.input-group>

    <x-admin.input-group for="fee" :label="__('Fee')" required>
        <x-admin.input-number prepend="MVR" name="fee" required :placeholder="__('Fee')" :value="old('fee', $service->fee)" />
    </x-admin.input-group>

    <x-slot:buttons>
        <x-admin.form-save-buttons :cancel-url="route('admin.services.index')" />
    </x-slot:buttons>

</x-admin.form-container>
