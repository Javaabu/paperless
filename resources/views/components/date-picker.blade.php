@props([
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => '',
    'disabled' => false,
    'required' => false,
    'helperText' => null
])

@php
    $value = old($name) ?? $value;
    $id ??= $name;
    $error_name = str_replace(['[', ']'], ['.', ''], $name);
@endphp

<div class="form-group w-100">
    <div class="input-group mb-0">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="{{ $icon ?? 'zmdi zmdi-calendar' }}"></i></span>
        </div>
        {!! Form::text($name, $value ?? old($name), [
        'type' => 'date',
        'id' => $id,
        'class' => add_error_class($errors->has($name), 'form-control date-picker'),
        'placeholder' => $placeholder,
        'autocomplete' => 'off',
        'disabled' => ! empty($disabled),
        'required' => ! empty($required),
        ]) !!}
        <div class="input-group-append">
            <div class="input-group-text input-group-text-link">
                <a href="#" data-date-clear="#{{ $name }}" class="disable-w-input text-body" title="{{ __('Clear') }}">
                    <i class="{{ $clear_icon ?? 'zmdi zmdi-close' }}"></i>
                </a>
            </div>
        </div>
        <i class="form-group__bar"></i>
        @include('errors._list', ['error' => $errors->get($error_name)])
    </div>
    @if($helperText)
        <small class="form-text text-muted">
            {{ $helperText }}
        </small>
    @endif
</div>
