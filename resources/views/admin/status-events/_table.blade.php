<x-forms::table
    model="status_events"
    :no-bulk="true"
    :no-checkbox="true"
    :no-pagination="true"
    table-class="mb-0"
>

    <x-slot:headers>
        <x-forms::table.heading :label="__('Date')" />
        <x-forms::table.heading :label="__('Status')" />
        <x-forms::table.heading :label="__('User')" />
        <x-forms::table.heading :label="__('Remarks')" />
    </x-slot:headers>

    <x-slot:rows>
        @if($status_events->isEmpty())
            <x-forms::table.empty-row columns="10" :no-checkbox="! empty($no_checkbox)">
                {{ __('No matching status events found.') }}
            </x-forms::table.empty-row>
        @else
            @include('paperless::admin.status-events._list')
        @endif
    </x-slot:rows>
</x-forms::table>

