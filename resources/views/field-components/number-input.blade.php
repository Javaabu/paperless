@php
    $name = $getName();
    $id = $getId();
    if ($isRepeatable()) {
        $repeating_instance = $getRepeatingInstance();
        $id .= "_{$repeating_instance}";
        $name = "{$getRepeatingGroupId()}[{$repeating_instance}][$name]";
    }

@endphp

<x-paperless::conditional-wrapper
    :is-conditional="$isConditional()"
    :is-checkbox="$isConditionalCheckbox()"
    enable-elem="#{{ $getConditionalOn() }}"
    :value="$getConditionalValue()"
    :hide-fields="$isReversedConditional()"
>
    <x-forms::number
        :label="$getLabel()"
        :name="$name"
        :value="$getState()"
        :placeholder="$getPlaceholder()"
        :required="$isMarkedAsRequired()"
        inline
    >
        @if(filled($getPrefix()))
            <x-slot:prepend>
                <div class="input-group-text prepend">{{ $getPrefix() }}</div>
            </x-slot:prepend>
        @endif

        @if(filled($getSuffix()))
            <x-slot:append>
                <div class="input-group-text append">{{ $getSuffix() }}</div>
            </x-slot:append>
        @endif
    </x-forms::number>
</x-paperless::conditional-wrapper>
