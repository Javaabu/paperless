<x-forms::select2
    :name="$getName()"
    :options="$getOptions()"
    :default="$getSelected()"
    :ajax-url="route('api.islands.index')"
    :filter-field="$getFilterBy()"
    :required="$isMarkedAsRequired()"
    :placeholder="$getPlaceholder() ?? ''"
    inline
/>
