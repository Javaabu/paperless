@props([
    'reference' => '#type',
    'json' => false,
    'values' => [],
    'with_wrapper' => true,
    'hideFields' => true,
    'reverse' => false,
])

@php
    if ($json) {
        $values = json_encode($values);
    } else {

        if (! is_array($values)) {
            $values = [$values];
        }

        $values = "[" . implode(',', $values) . "]";
    }
@endphp

<div
    {{ $attributes }}
    @if ($with_wrapper) class="row" @endif
    data-enable-elem="{{ $reference }}"
    data-enable-section-value="{{ $values }}"
    data-hide-fields="{{ $hideFields ? 'true' : 'false' }}"
    data-disable="{{ $reverse ? 'true' : 'false' }}"
{{--    style="display: none"--}}
>
    @if ($with_wrapper) <div class="col"> @endif
        {{ $slot }}
        @if ($with_wrapper) </div> @endif
</div>
