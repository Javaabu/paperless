@php
    $name = $getName();
    $id = $getId();
    if ($isRepeatable()) {
        $repeating_instance = $getRepeatingInstance();
        $id .= "_{$repeating_instance}";
        $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
    }

    if ($isMultiple()) {
        $name .= '[]';
    }

    $class = '';
    if ($hidden_input_helper_class = $getHiddenInputHelperClassName()) {
        $class .= " {$hidden_input_helper_class}";
    }

@endphp

<x-forms::select2
    :label="$getLabel()"
    :name="$name"
    :id="$id"
    :options="$getOptions()"
    :multiple="$isMultiple()"
    :required="$isMarkedAsRequired()"
    :placeholder="$getPlaceholder() ?? ''"
    :child="$getChild()"
    :default="$getState()"
    inline
    class="{{ $class }}"
/>
