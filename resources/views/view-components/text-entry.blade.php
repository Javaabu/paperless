@props([
    'label',
    'danger' => false
])

<dl {{ $attributes->merge(['class' => 'row mb-1']) }}>
    <dt class="col-sm-6 col-md-4 {{ $danger ? 'text-danger' : '' }}">
        {{ $getLabel() }} {{ $isMarkedAsRequired() ? '*' : '' }}
    </dt>
    <dd class="col-sm-6 col-md-8">{!! $getValue() !!}</dd>
</dl>
