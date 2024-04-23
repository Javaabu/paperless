<x-forms::select2
    :name="$getName()"
    :options="$getOptions()"
    :default="$getSelected()"
    :ajax-url="$getApiUrl()"
    :filter-field="$getFilterBy()"
    :required="$isMarkedAsRequired()"
    :placeholder="$getPlaceholder() ?? ''"
    inline
/>
