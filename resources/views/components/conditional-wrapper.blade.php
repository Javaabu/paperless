@props([
    'isConditional' => false,
    'enableElem',
    'value',
    'hideFields' => false,
    'class' => 'mb-5',
    'isCheckbox' => false,
])

@if($isConditional)
    @if($isCheckbox)
        <div id="some-fields" data-enable-section-checkbox="{{ $enableElem }}" data-hide-fields="true">
            {{ $slot }}
        </div>
    @else
        <x-forms::conditional-wrapper :class="$class" :enable-elem="$enableElem" :enable-value="$value" :hide-fields="true" :disable="$hideFields">
            {{ $slot }}
        </x-forms::conditional-wrapper>
    @endif
@else
    {{ $slot }}
@endif
