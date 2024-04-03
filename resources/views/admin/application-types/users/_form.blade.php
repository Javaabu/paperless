<x-admin.form-container>

    <x-admin.input-group for="user" :label="__('User')" required>
        @php
            $users = \App\Models\User::pluck('name', 'id');
        @endphp
        <x-admin.select name="user"
                        :value="old('user')"
                        :options="$users"
                        required/>
    </x-admin.input-group>

    <x-admin.input-group for="is_active" :label="__('Is Active')">
        <x-admin.checkbox name="is_active" :label="__('Yes')" value="1"/>
    </x-admin.input-group>

    <x-slot:buttons>
        <x-admin.form-save-buttons :cancel-url="route('admin.application-types.assigned-users.index', $application_type)"/>
    </x-slot:buttons>

</x-admin.form-container>
