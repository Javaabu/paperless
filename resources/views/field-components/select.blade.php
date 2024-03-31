<x-paperless::input-group
    for="{{ $getId() }}"
    :label="$getLabel()"
    :required="$isMarkedAsRequired()"
>
    <x-paperless::select
        name="{{ $getName() }}{{ $isMultiple() ? '[]' : '' }}"
        :options="$getOptions()"
        :multiple="$isMultiple()"
        :required="$isMarkedAsRequired()"
        :value="$getState()"
        :placeholder="$getPlaceholder()"
    />

</x-paperless::input-group>
