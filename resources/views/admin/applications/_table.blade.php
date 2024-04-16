<x-forms::table
    model="applications"
    :no-bulk="! empty($no_bulk)"
    :no-checkbox="! empty($no_checkbox)"
    :no-pagination="! empty($no_pagination)"
>

    @if(empty($no_bulk))
        <x-slot:bulk-form :action="route('admin.applications.bulk')">
            @include('paperless::admin.applications._bulk')
        </x-slot:bulk-form>
    @endif

    <x-slot:headers>
        <x-forms::table.heading :label="__('Reference')" />
        <x-forms::table.heading :label="__('Application Type')" />
        <x-forms::table.heading :label="__('Applicant')" />
        <x-forms::table.heading :label="__('Generated')" />
        <x-forms::table.heading :label="__('Submitted Date')" />
        <x-forms::table.heading :label="__('Status')" />
    </x-slot:headers>

    <x-slot:rows>
        @if($applications->isEmpty())
            <x-forms::table.empty-row columns="10" :no-checkbox="! empty($no_checkbox)">
                {{ __('No matching applications found.') }}
            </x-forms::table.empty-row>
        @else
            @include('paperless::admin.applications._list')
        @endif
    </x-slot:rows>

    @if(empty($no_pagination))
        <x-slot:pagination>
            {{ $applications->links('forms::material-admin-26.pagination') }}
        </x-slot:pagination>
    @endif
</x-forms::table>
