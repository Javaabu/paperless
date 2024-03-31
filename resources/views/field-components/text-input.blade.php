<x-paperless::input-group
    for="{{ $getId() }}"
    :label="$getLabel()"
    :required="$isMarkedAsRequired()"
>
    @php
        $name = $getName();
        $id = $getId();
        if ($isRepeatable()) {
            $repeating_instance = $getRepeatingInstance();
            $id .= "_{$repeating_instance}";
            $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
        }

    @endphp
    <x-paperless::text-input
        :type="$getType()"
        :dv="$hasDhivehiInput()"
        id="{{ $id }}"
        name="{{ $name }}"
        :required="$isMarkedAsRequired()"
        :value="$getState()"
        :placeholder="$getPlaceholder()"
    />
</x-paperless::input-group>
