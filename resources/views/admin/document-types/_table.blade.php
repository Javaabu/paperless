<x-forms::table
    model="document_types"
    :no_bulk="! empty($no_bulk)"
    :no_checkbox="! empty($no_checkbox)"
    :no_pagination="! empty($no_pagination)"
>
    @if(empty($no_bulk))
        <x-slot:bulk-form :action="route('admin.document-types.bulk')">
            @include('paperless::admin.document-types._bulk')
        </x-slot:bulk-form>
    @endif

    <x-slot:headers>
        <x-forms::table.heading :label="__('Name')" sortable="name" />
        <x-forms::table.heading :label="__('Slug')" />
        <x-forms::table.heading :label="__('Description')" />
    </x-slot:headers>

    <x-slot:rows>
        @if($document_types->isEmpty())
            <x-forms::table.empty-row columns="3" :no-checkbox="! empty($no_checkbox)">
                {{ __('No matching document types found.') }}
            </x-forms::table.empty-row>
        @else
            @include('paperless::admin.document-types._list')
        @endif
    </x-slot:rows>

    @if(empty($no_pagination))
        <x-slot:pagination>
            {{ $document_types->links('forms::material-admin-26.pagination') }}
        </x-slot:pagination>
    @endif
</x-forms::table>
