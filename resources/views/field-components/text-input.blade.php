@php
    $name = $getName();
    $id = $getId();
    if ($isRepeatable()) {
        $repeating_instance = $getRepeatingInstance();
        $id .= "_{$repeating_instance}";
        $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
    }

@endphp

<x-forms::text
    :label="$getLabel()"
    :name="$name"
    :show-label="$labelShouldBeHidden()"
    :value="$getState()"
    :hidden="$isHidden()"
    :placeholder="$getPlaceholder()"
    :required="$isMarkedAsRequired()"
    inline
/>
