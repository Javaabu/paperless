<x-forms::table
    model="services"
    :no-bulk="! empty($no_bulk)"
    :no-checkbox="! empty($no_checkbox)"
    :no-pagination="! empty($no_pagination)"
>

    @if(empty($no_bulk))
        <x-slot:bulk-form :action="route('admin.services.bulk')">
            @include('paperless::admin.services._bulk')
        </x-slot:bulk-form>
    @endif

    <x-slot:headers>
        <x-forms::table.heading :label="__('Name')" sortable="name" />
        <x-forms::table.heading :label="__('Code')" sortable="code" />
        <x-forms::table.heading :label="__('Fee')" />
    </x-slot:headers>

    <x-slot:rows>
        @if($services->isEmpty())
            <x-forms::table.empty-row columns="10" :no-checkbox="! empty($no_checkbox)">
                {{ __('No matching services found.') }}
            </x-forms::table.empty-row>
        @else
            @include('paperless::admin.services._list')
        @endif
    </x-slot:rows>

    @if(empty($no_pagination))
        <x-slot:pagination>
            {{ $services->links('forms::material-admin-26.pagination') }}
        </x-slot:pagination>
    @endif
</x-forms::table>
