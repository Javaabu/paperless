@props([
    'label' => '',
    'color' => 'primary',
    'type' => 'pill',
    'link' => null,
])

<a href="{{ $link }}"
    {{
        $attributes->class([
            'badge',
            'badge-pill' => $type == 'pill',
            'badge-primary' => $color == 'primary',
            'badge-secondary' => $color == 'secondary',
            'badge-success' => $color == 'success',
            'badge-danger' => $color == 'danger',
            'badge-warning' => $color == 'warning',
            'badge-info' => $color == 'info',
            'badge-light' => $color == 'light',
            'badge-dark' => $color == 'dark',
        ])
    }}
>
    {{ $label }}{{ $slot }}
</a>
