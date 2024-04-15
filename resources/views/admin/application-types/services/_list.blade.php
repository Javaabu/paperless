@foreach($services as $service)
    <x-forms::table.row :model="$service" :no-checkbox="! empty($no_checkbox)">

        <x-forms::table.cell :label="__('Name')">
            {!! $service->admin_link !!}
            <div class="table-actions actions">
                <a class="actions__item"><span>{{ __('ID: :id', ['id' => $application_type->id]) }}</span></a>

            </div>
        </x-forms::table.cell>

        <x-forms::table.cell name="amount">
            {{ format_currency($service->fee) }}
        </x-forms::table.cell>
        <x-forms::table.cell name="is_applied_automatically">
            <span class="badge {{ $service->pivot->is_applied_automatically ? 'badge-success' : 'badge-secondary' }}">
                {{ $service->pivot->is_applied_automatically ? __('Yes') : __('No') }}
            </span>
        </x-forms::table.cell>
    </x-forms::table.row>
@endforeach
