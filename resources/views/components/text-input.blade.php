@props([
    'name',
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'dv' => false,
    'en' => false,
    'disabled' => false,
    'id' => null,
    'helperText' => null,
    'prepend' => null,
])
@php
    if (! in_array($type, ['text', 'password', 'email', 'number'])) {
        $type = 'text';
    }

    $dv = $dv ? ' dv' : '';
    $en = $en ? ' en' : '';

    $field_value = old($name);
    if (! $field_value) {
        $field_value = $value;
    }

    $attribs = [
        'class' => add_error_class($errors->has($name)) . $dv . $en,
        'placeholder' => $placeholder,
        'id' => $id ?? $name,
        'disabled' => $disabled,
        'value' => $field_value,
    ];

    $extra_attributes = (new \Javaabu\Paperless\Support\HtmlBuilder())->attributes($attribs);
    $input_attributes = new \Illuminate\Support\HtmlString($extra_attributes);
    $error_name = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div class="form-group mb-0 w-100">
    <div class="input-group mb-0">
        <input type="{{ $type }}" name="{{ $name }}" {{ $attributes }} {{ $input_attributes }} {{ $required ? 'required' : '' }}/>
        @if ($prepend)
            <div class="input-group-append">
                <span class="input-group-text">{{ $prepend }}</span>
            </div>
        @endif
        <i class="form-group__bar"></i>
        @include('errors._list', ['error' => $errors->get($error_name)])
    </div>
    @if($helperText)
        <small class="form-text text-muted">
            {{ $helperText }}
        </small>
    @endif

    {{ $helper ?? "" }}
    {{ $slot }}
</div>
