@php
    $name = $getName();
    $id = $getId();
@endphp

<x-forms::checkbox
    class="mt-2"
    :label="$getLabel()"
    :name="$name"
    :value="$getState()"
    :required="$isMarkedAsRequired()"
    :default="$getState()"
    inline
/>
