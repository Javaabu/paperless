@php
    $name = $getName();
    $id = $getId();

    if ($isRepeatable()) {
        $repeating_instance = $getRepeatingInstance();
        $id .= "_{$repeating_instance}";
        $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
    }
@endphp


<x-forms::file
    :label="$getLabel()"
    :name="$name"
    :required="$isMarkedAsRequired()"
    :default="$getState()"
    inline
/>
