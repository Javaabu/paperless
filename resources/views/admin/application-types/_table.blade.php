<x-forms::table
    model="application_types"
    :no-bulk="! empty($no_bulk)"
    :no-checkbox="! empty($no_checkbox)"
    :no-pagination="! empty($no_pagination)"
>

    <x-slot:headers>
        <th data-sort-field="name" class="{{ add_sort_class('name') }}">{{ __('Name') }}</th>
        <th>{{ __('Document Types') }}</th>
        <th>{{ __('Services') }}</th>
        <th>{{ __('Applications') }}</th>
        <th>{{ __('ETA') }}</th>
        <th>{{ __('Category') }}</th>
    </x-slot:headers>

    <x-slot:rows>
        @if($application_types->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching application types found.') }}</td>
            </tr>
        @else
            @include('paperless::admin.application-types._list')
        @endif
    </x-slot:rows>

    @if(empty($no_pagination))
        <x-slot:pagination>
            {{ $application_types->links('forms::material-admin-26.pagination') }}
        </x-slot:pagination>
    @endif
</x-forms::table>
