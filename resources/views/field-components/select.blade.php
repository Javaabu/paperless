<x-forms::select2
    name="{{ $getName() }}{{ $isMultiple() ? '[]' : '' }}"
    :options="$getOptions()"
    :multiple="$isMultiple()"
    :required="$isMarkedAsRequired()"
    :placeholder="$getPlaceholder() ?? ''"
    :child="$getChild()"
    :default="$getState()"
    inline
/>

