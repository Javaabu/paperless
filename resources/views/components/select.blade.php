@props([
    'name',
    'options',
    'id' => null,
    'value' => null,
    'class' => '',
    'ajaxUrl' => null,
    'filterField' => null,
    'selectChild' => null,
    'dropDownParentElem' => null,
    'nameField' => 'name',
    'attribs' => [],
    'helperText' => null,
    'multiple' => false,
    'placeholder' => null,
    'disabled' => false,
    'data_tags' => false,
])

@php
    $value = $value ?? old($name);
    $error_name = str_replace(['[', ']'], ['.', ''], $name);

    $attribs = array_merge([
        'class' => add_error_class($errors->has($error_name)) . ' select2-basic ' . $class,
        'data-allow-clear' => 'true',
        'data-tags' => $data_tags,
        'data-name-field' => $nameField,
        'data-selected-value' => !is_array($value) ? $value : null,
        'id' => $id ?? $name,
        'data-placeholder' => $placeholder ?: __('Not Selected')
    ], $attribs);

    if ($multiple) {
        $attribs['multiple'] = 'multiple';
    }

    if ($disabled) {
        $attribs['disabled'] = true;
    }

    if ($filterField) {
        $attribs = array_merge($attribs, [
            'data-filter-field' => $filterField,
        ]);
    }

    if ($dropDownParentElem) {
        $attribs = array_merge($attribs, [
            'data-dropdown-parent-elem' => $dropDownParentElem,
        ]);
    }

    if ($selectChild) {
        $attribs = array_merge($attribs, [
            'data-select-child' => $selectChild,
        ]);
    }

    if ($ajaxUrl) {
        $attribs = array_merge($attribs, [
            'data-select-ajax-url' => $ajaxUrl,
        ]);
    }

    $attributes = (new \Javaabu\Paperless\Support\HtmlBuilder())->attributes($attribs);
    $select_element = new \Illuminate\Support\HtmlString($attributes);
    $error = $errors->get($error_name);
@endphp

<div class="form-group input-group mb-0 w-100">
    <div class="input-group mb-0">
        <select name="{{ $name }}" {{ $select_element }}>
            @foreach ($options as $key => $label)
                @if ($multiple && is_array($value))
                    @php $selected = in_array($key, $value) @endphp
                @else
                    @php $selected = $key == $value; @endphp
                @endif
                <option value="{{ $key }}" @selected($selected)>{{ $label }}</option>
            @endforeach
        </select>
        {{ $slot }}
    </div>

    @unless(empty($error))
        <ul class="invalid-feedback">
            @foreach($error as $error_text)
                <li>{{ $error_text }}</li>
            @endforeach
        </ul>
    @endunless

    @if($helperText)
        <small class="form-text text-muted">
            {{ $helperText }}
        </small>
    @endif
</div>
