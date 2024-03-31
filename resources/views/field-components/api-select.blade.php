<x-paperless::input-group
    for="{{ $getId() }}"
    :label="$getLabel()"
    :required="$isMarkedAsRequired()"
>
    <x-paperless::api-select
        name="{{ $getName() }}"
        name-field="{{ $getNameField() }}"
        :selected="$getSelected()"
        ajax-url="{{ $getApiUrl() }}"
        :filter-by="$getFilterBy()"
        :required="$isMarkedAsRequired()"
        :placeholder="$getPlaceholder()"
    />
</x-paperless::input-group>
