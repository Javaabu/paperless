@props([
    'label' => '',
    'color' => 'primary',
    'pill' => false,
])

<span
    {{
        $attributes->class([
            'badge',
            'badge-pill' =>$pill, // pill or square
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
</span>
