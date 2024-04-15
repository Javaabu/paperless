<x-forms::table
    model="services"
    :no-bulk="! empty($no_bulk)"
    :no-checkbox="! empty($no_checkbox)"
    :no-pagination="! empty($no_pagination)"
>

    <x-slot:headers>
        <th>{{ __('Service') }}</th>
        <th>{{ __('Amount') }}</th>
        <th>{{ __('Is Applied Automatically') }}</th>
    </x-slot:headers>

    <x-slot:rows>
        @if($services->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching services found.') }}</td>
            </tr>
        @else
            @include('paperless::admin.application-types.services._list')
        @endif
    </x-slot:rows>

    @if(empty($no_pagination))
        <x-slot:pagination>
            {{ $services->links('forms::material-admin-26.pagination') }}
        </x-slot:pagination>
    @endif
</x-forms::table>

