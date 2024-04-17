@props([
    'title' => null,
    'icon' => null,
    'color' => 'success',
])

<div {{
    $attributes->class([
        'alert',
        'd-flex align-items-center' => ! empty($icon),
        'alert-success' => $color === 'success',
        'alert-primary' => $color === 'primary',
        'alert-warning' => $color === 'warning',
        'alert-info' => $color === 'info',
        'alert-dark' => $color === 'dark',
        'alert-light' => $color === 'light',
        'alert-danger' => $color === 'danger',
    ])
}} role="alert">

    @if ($icon)
    <div class="mr-4">
        <i style="font-size: 5rem;" class="fa-duotone fa-{{ $icon }}"></i>
    </div>
    @endif
    <div>
        @if ($title)
        <h4 class="alert-heading">{{ $title }}</h4>
        @endif
        {{ $slot }}
    </div>
</div>
