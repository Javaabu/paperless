@props([
    'name',
    'ajaxUrl',
    'selected' => null,
    'selectChild' => null,
    'filterField' => null,
    'filterBy' => null,
    'id' => null,
    'idField' => 'id',
    'createUrl' => null,
    'createModel' => null,
    'nameField' => 'name',
    'tableName' => '',
    'placeholder' => null,
    'lang' => 'en',
    'attribs' => [],
    'helperText' => null,
    'dataTags' => false
])
<?php

/*
 * Docs:
 * To add extra params, pass them as attributes
 * */
$id ??= $name;
$selected_id = null;
$options = [];

if (is_object($selected)) {
    $selected_id = $selected->{$idField};
    $option_id = $selected->{$idField};
    $option_name = $selected->formatted_name ?? $selected->name;
    $options = [$option_id => $option_name];
}

$query_params = $filterBy ? http_build_query($filterBy) : '';
$ajaxUrl = $ajaxUrl . '?' . $query_params;

$attribs = array_merge([
    'class' => add_error_class($errors->has($name)) .' select2-ajax',
    'data-select-ajax-url' => $ajaxUrl,
    'data-placeholder' => $placeholder ?: __('Nothing Selected'),
    'data-allow-clear' => 'true',
    'data-table-name' => $tableName,
    'data-dir' => $lang == 'dv' ? 'rtl' : 'ltr',
    'data-lang' => $lang,
    'data-tags' => "$dataTags",
    'data-name-field' => $nameField,
    'data-id-field' => $idField,
    'id' => $id,
    'required' => ! empty($required),
], $attribs);

if ($selectChild) {
    $attribs = array_merge($attribs, [
        'data-select-child' => $selectChild,
    ]);
}

if ($filterField) {
    $attribs = array_merge($attribs, [
        'data-filter-field' => $filterField,
    ]);
}

$error_name = str_replace(['[', ']'], ['.', ''], $name);
?>


<div class="form-group w-100 mb-0">

    @if ($createUrl != null)
        @can('create', $createModel)
            <div class="input-group mb-0">
                @endcan
                @endif
                {!! Form::select($name, $options, $selected_id, $attribs) !!}
                <i class="form-group__bar"></i>

                @if ($createUrl != null)
                    @can('create', $createModel)
                        <div class="input-group-append">
                            <a class="btn btn-light" href="{{ $createUrl }}" target="_blank">
                                <i class="fal fa-plus"></i>
                            </a>
                        </div>
            </div>
        @endcan
    @endif

    {{ $slot }}
    @include('errors._list', ['error' => $errors->get($error_name)])
    @if($helperText)
        <small class="form-text text-muted">
            {{ $helperText }}
        </small>
    @endif
</div>
