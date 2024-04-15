<x-forms::table
    model="document_types"
    :no-bulk="! empty($no_bulk)"
    :no-checkbox="! empty($no_checkbox)"
    :no-pagination="! empty($no_pagination)"
>

    <x-slot:headers>
        <th>{{ __('Document Type') }}</th>
        <th>{{ __('Is Required') }}</th>
    </x-slot:headers>

    <x-slot:rows>
        @if($document_types->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching document types found.') }}</td>
            </tr>
        @else
            @include('paperless::admin.application-types.document-types._list')
        @endif
    </x-slot:rows>

    @if(empty($no_pagination))
        <x-slot:pagination>
            {{ $document_types->links('forms::material-admin-26.pagination') }}
        </x-slot:pagination>
    @endif
</x-forms::table>

