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

<x-forms::date :name="$name"
               :required="$required"
               :id="$id"
               :show-label="false"
               :value="$value"
               :placeholder="$placeholder"
               :disabled="$disabled"
               inline/>
