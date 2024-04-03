@component('admin.components.filter')
    <div class="row">
        <div class="col-md-3">
            <x-admin.input-group for="search" :label="__('Search')" stacked>
                <x-admin.input-text name="search" :placeholder="__('Search...')" :value="request()->input('search', old('search'))" />
            </x-admin.input-group>
        </div>
        <div class="col-md-3">
            @include('admin.components.per-page')
        </div>
        <div class="col-md-3">
            @component('admin.components.filter-submit', [
                'filter_url' => route('admin.application-types.assigned-users.index', $application_type),
                'export' => true,
            ])
            @endcomponent
        </div>
    </div>
@endcomponent
