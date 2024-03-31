@props([
    'label' => null,
    'for' => '',
    'stacked' => false,
    'required' => false,
    'hidden' => false,
    'extraClasses' => ''
])

<div @class([
        $extraClasses,
        "row" => !$stacked,
        "d-none" => $hidden,
    ]) {{ $attributes }}>

    @if (! $stacked)
        <div class="col-sm-2 col-form-label">
            @endif

            @if ($label)
                <label for="{{ $for }}">{{ $label }}{{ $required ? ' *' : '' }}</label>
            @endif

            @if (! $stacked)
        </div>
    @endif

    <div class="form-group {{ $stacked ? "" : "col-sm-10" }} d-flex align-items-center w-100 mb-0">
        {{ $slot }}
    </div>
</div>
