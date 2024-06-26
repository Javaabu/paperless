@php
    $name = $getName();
    $id = $getId();
    if ($isRepeatable()) {
        $repeating_instance = $getRepeatingInstance();
        $id .= "_{$repeating_instance}";
        $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
    }

@endphp

<x-forms::number
    :label="$getLabel()"
    :name="$name"
    :value="$getState()"
    :placeholder="$getPlaceholder()"
    :required="$isMarkedAsRequired()"
    inline
/>
