<x-forms::select2
    :name="$getName()"
    :ajax-url="route('api.islands.index')"
    :filter-field="$getFilterBy()"
    :required="$isMarkedAsRequired()"
    :placeholder="$getPlaceholder() ?? ''"
    inline
/>
