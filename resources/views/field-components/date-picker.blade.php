@php
    $name = $getName();
    $id = $getId();
    if ($isRepeatable()) {
        $repeating_instance = $getRepeatingInstance();
        $id .= "_{$repeating_instance}";
        $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
    }
@endphp

<x-paperless::input-group
    for="{{ $id }}"
    :label="$getLabel()"
    :required="$isMarkedAsRequired()"
>
    <x-paperless::date-picker
        :id="$id"
        :name="$name"
        :required="$isMarkedAsRequired()"
        :value="$getState()"
        :placeholder="$getPlaceholder()"
    />
</x-paperless::input-group>
