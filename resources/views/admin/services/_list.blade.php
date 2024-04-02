@foreach($services as $service)
    @component('admin.components.table-row', [
            'model' => 'services',
            'model_id' => $service->id,
            'no_checkbox' => ! empty($no_checkbox),
        ])

    @slot('columns')
        <td data-col="{{ __('Name') }}">
            {!! $service->admin_link !!}
            <x-admin.inline-actions :model="$service" />
        </td>
        <td data-col="{{ __('Code') }}">{{ $service->code }}</td>
        <td data-col="{{ __('Fee') }}">{{ format_currency($service->fee) }}</td>
    @endslot

    @endcomponent
@endforeach
