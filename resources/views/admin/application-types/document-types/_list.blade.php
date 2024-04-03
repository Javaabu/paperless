@foreach($document_types as $document_type)
    @component('admin.components.table-row', [
            'model' => 'document_types',
            'model_id' => $document_type->pivot->id,
            'no_checkbox' => ! empty($no_checkbox),
        ])

        @slot('columns')
            <td data-col="{{ __('Document Type') }}">
                {!! $document_type->admin_link !!}
                <x-admin.nested-inline-actions
                    :actions="[
                        'delete' => route('admin.application-types.document-types.destroy', [$application_type, $document_type]),
                    ]"
                    :parent-model="$application_type"
                    :id="$document_type->pivot->id" />
            </td>
            <td data-col="{{ __('Is Required') }}">
                <x-badge :color="$document_type->pivot->is_required ? 'success' : 'secondary'">
                    {{ $document_type->pivot->is_required ? __('Yes') : __('No') }}
                </x-badge>
            </td>
        @endslot
     @endcomponent
@endforeach
