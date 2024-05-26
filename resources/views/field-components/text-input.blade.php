@php
    $name = $getName();
    $id = $getId();
    if ($isRepeatable()) {
        $repeating_instance = $getRepeatingInstance();
        $id .= "_{$repeating_instance}";
        $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
    }

    $class = '';

    if ($getIsHelperField()) {
        $class = ' linked-hidden-input';
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
    data-parent-selector="{{ $getChildSelector() }}"
    data-api-url="{{ filled($getApiUrl()) ? (route($getApiUrl()) . '/:id') : null }}"
    data-target-column="{{ $getApiTargetColumn() }}"
    class="{{ $getIsHelperField() ? ' linked-hidden-input' : null }}"
/>
