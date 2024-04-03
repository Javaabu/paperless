@foreach($services as $service)
    @component('admin.components.table-row', [
            'model' => 'services',
            'model_id' => $service->pivot->id,
            'no_checkbox' => ! empty($no_checkbox),
        ])

        @slot('columns')
            <td data-col="{{ __('Document Type') }}">
                {!! $service->admin_link !!}
                <x-admin.nested-inline-actions
                    :actions="[
                        'delete' => route('admin.application-types.services.destroy', [$application_type, $service]),
                    ]"
                    :parent-model="$application_type"
                    :id="$service->pivot->id" />
            </td>
            <td data-col="{{ __('Amount') }}">
                {{ format_currency($service->fee) }}
            </td>
            <td data-col="{{ __('Is Applied Automatically') }}">
                <x-badge :color="$service->pivot->is_applied_automatically ? 'success' : 'secondary'">
                    {{ $service->pivot->is_applied_automatically ? __('Yes') : __('No') }}
                </x-badge>
            </td>
        @endslot
     @endcomponent
@endforeach
