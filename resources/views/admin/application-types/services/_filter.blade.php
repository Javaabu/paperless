<x-forms::filter>
    <div class="row">
        <div class="col-md-3">
            <x-forms::text name="search" :label="__('Search')" :placeholder="__('Search..')" :show-errors="false" :inline="false" />
        </div>
        <div class="col-md-3">
            <x-forms::per-page />
        </div>
        <div class="col-md-3">
            <x-forms::filter-submit :cancel-url="route('admin.application-types.services.index', $application_type)" :export="false" />
        </div>
    </div>
</x-forms::filter>
