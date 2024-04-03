<x-admin.form-container>

    <x-admin.input-group for="service" :label="__('Service')" required>
        @php
            $services = \App\Models\Service::pluck('name', 'id');
        @endphp
        <x-admin.select name="service"
                        :value="old('service')"
                        :options="$services"
                        required/>
    </x-admin.input-group>

    <x-admin.input-group for="is_applied_automatically" :label="__('Is Applied Automatically')" extra-classes="align-items-start">
        <x-admin.checkbox class="mt-2" name="is_applied_automatically" :label="__('Yes')" value="1">
            <small class="form-text text-muted">
                {{ __('If selected, a payment will be automatically initiated upon verification of this type of application. If not selected, the application must undergo an external process for payment initiation.') }}
            </small>
        </x-admin.checkbox>
    </x-admin.input-group>

    <x-slot:buttons>
        <x-admin.form-save-buttons :cancel-url="route('admin.application-types.services.index', $application_type)"/>
    </x-slot:buttons>

</x-admin.form-container>
