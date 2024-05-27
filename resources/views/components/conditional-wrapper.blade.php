@props([
    'isConditional' => false,
    'enableElem',
    'value',
    'hideFields' => false,
])



@if($isConditional)
<x-forms::conditional-wrapper :enable-elem="$enableElem" :enable-value="$value" :hide-fields="true" :disable="$hideFields">
    {{ $slot }}
</x-forms::conditional-wrapper>
@else
    {{ $slot }}
@endif
