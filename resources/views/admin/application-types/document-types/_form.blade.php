<x-admin.form-container>

    <x-admin.input-group for="document_type" :label="__('Document Type')" required>
        @php
            $document_types = \App\Models\DocumentType::pluck('name', 'id');
        @endphp
        <x-admin.select name="document_type"
                        :value="old('document_type')"
                        :options="$document_types"
                        required/>
    </x-admin.input-group>

    <x-admin.input-group for="is_required" :label="__('Is Required')">
        <x-admin.checkbox name="is_required" :label="__('Yes')" value="1"/>
    </x-admin.input-group>

    <x-slot:buttons>
        <x-admin.form-save-buttons :cancel-url="route('admin.application-types.document-types.index', $application_type)"/>
    </x-slot:buttons>

</x-admin.form-container>
