<x-paperless::input-group
    for="{{ $getId() }}"
    :label="$getLabel()"
    :required="$isMarkedAsRequired()"
>
    <x-paperless::textarea
        :dv="$hasDhivehiInput()"
        name="{{ $getName() }}"
        :required="$isMarkedAsRequired()"
        :value="$getState()"
        :placeholder="$getPlaceholder()"
    />
</x-paperless::input-group>
