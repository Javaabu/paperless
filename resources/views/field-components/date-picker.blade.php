@php
    $name = $getName();
    $id = $getId();
    if ($isRepeatable()) {
        $repeating_instance = $getRepeatingInstance();
        $id .= "_{$repeating_instance}";
        $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
    }
@endphp

<x-forms::date
    :id="$id"
    :name="$name"
    :label="$getLabel()"
    :required="$isMarkedAsRequired()"
    :value="$getState()"
    :placeholder="$getPlaceholder()"
    inline
/>
