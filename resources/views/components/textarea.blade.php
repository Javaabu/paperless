@props([
    'name',
    'id' => null,
    'value' => null,
    'required' => false,
    'placeholder' => '',
    'dv' => false,
    'en' => false,
    'customClass' => '',
    'customAttribs' => [],
    'helperText' => null
])

@php
    $value ??= old($name);
    $error_name = str_replace(['[', ']'], ['.', ''], $name);
    $id ??= $name;
    $dv = $dv ? ' dv' : '';
    $en = $en ? ' en' : '';
    // Add dv to wysiwyg editor
    if (str_contains($customClass, "wysiwyg") && $dv == ' dv') {
        $customAttribs = array_merge($customAttribs, [
            'data-lang' => 'dv',
            'data-directionality' => 'rtl',
            ]);
    }

    $attribs = array_merge([
        'class' => "$customClass " . add_error_class($errors->has($error_name)).' auto-size' . $dv . $en,
        'rows' => 3,
        'id' => $id,
        'required' => $required,
        'placeholder' => __($placeholder)
        ], $customAttribs);


    $attributes_builder = (new \App\Helpers\FormBuilder\HtmlBuilder())->attributes($attribs);
    $element_attributes = new \Illuminate\Support\HtmlString($attributes_builder);
@endphp

<div class="form-group w-100">
    <div class="input-group mb-0">
        <textarea name="{{ $name }}" {{ $element_attributes }}>{{ $value }}</textarea>
        @include('errors._list', ['error' => $errors->get($error_name)])
        <i class="form-group__bar"></i>
    </div>
    @if($helperText)
        <small class="form-text text-muted">
            {{ $helperText }}
        </small>
    @endif
    {{ $slot }}
</div>
