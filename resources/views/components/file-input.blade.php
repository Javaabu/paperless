@props([
    'name',
    'file_url',
    'accept' => \Javaabu\Helpers\Media\AllowedMimeTypes::getAllowedMimeTypesString('document'),
])

<?php
    $attribs = $attribs ?? [];
    $attribs = array_merge([
        'class' => 'form-control',
        'accept' => $accept,
    ], $attribs);

    $error_name = str_replace(['[', ']'], ['.', ''], $name);
    $file_input_errors = $errors->get($error_name);

    $extra_attributes = (new \Javaabu\Paperless\Support\HtmlBuilder())->attributes($attribs);
    $input_attributes = new \Illuminate\Support\HtmlString($extra_attributes);
?>

<div class="d-flex fileinput mb-0 {{ ! empty($file_url) ? 'fileinput-exists' : 'fileinput-new' }}"
     data-provides="fileinput">
    <span class="btn btn-primary btn-file m-r-10">
        <span class="fileinput-new">{{ __('Select file') }}</span>
        <span class="fileinput-exists">{{ __('Change') }}</span>
        <input name="{{ $name }}" type="file" {{ $input_attributes }}>
    </span>
    <div class="d-flex flex-grow-1 min-h-100 align-items-center" style="border:1px solid #ddd;">
        <span class="fileinput-filename pl-2" >
            @unless(empty($file_url))
                <a href="{{ $file_url }}" target="_blank">
                    <i class="zmdi zmdi-open-in-new"></i> {{ $file_url }}
                </a>
            @endunless
        </span>
        <a href="#" class="close fileinput-exists" style="top:9px; right: 7px;" data-dismiss="fileinput">&times;</a>
    </div>
</div>

@unless(empty($file_input_errors))
    <ul class="invalid-feedback">
        @foreach($file_input_errors as $error_text)
            <li>{{ $error_text }}</li>
        @endforeach
    </ul>
@endunless
