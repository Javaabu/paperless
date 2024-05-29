@props([
    'isConditional' => false,
    'enableElem',
    'value',
    'hideFields' => false,
    'class' => 'mb-5'
])

@if($isConditional)
<x-forms::conditional-wrapper :class="$class" :enable-elem="$enableElem" :enable-value="$value" :hide-fields="true" :disable="$hideFields">
    {{ $slot }}
</x-forms::conditional-wrapper>
@else
    {{ $slot }}
@endif
