<x-paperless::input-group
    for="{{ $getId() }}"
    :label="$getLabel()"
    :required="$isMarkedAsRequired()"
>
    <x-paperless::date-picker
        id="{{ $getId()  }}"
        name="{{ $getName()}}"
        :required="$isMarkedAsRequired()"
        :value="$getState()"
        :placeholder="$getPlaceholder()"
    />
</x-paperless::input-group>


