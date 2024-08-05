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

<x-paperless::conditional-wrapper
    :is-conditional="$isConditional()"
    :is-checkbox="$isConditionalCheckbox()"
    enable-elem="#{{ $getConditionalOn() }}"
    :value="$getConditionalValue()"
    :hide-fields="$isReversedConditional()"
>
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
    >
        @if(filled($getPrefix()))
            <x-slot:prepend>
                <div class="input-group-text prepend">{{ $getPrefix() }}</div>
            </x-slot:prepend>
        @endif
    </x-forms::text>
</x-paperless::conditional-wrapper>
