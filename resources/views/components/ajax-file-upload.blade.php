@props([
    'name',
    'model',
    'document' => null,
    'id' => null,
    'documentType' => null,
])

@php
    $model_type = $model->getMorphClass();
    $model_id = $model->id;
    $id ??= $name;
@endphp
<div class="d-flex w-100 ajax-upload"
     data-model-type="{{ $model_type }}"
     data-model-id="{{ $model_id }}"
     data-document-type="{{ $documentType?->id }}"
     data-field-name="{{ $name }}"
>
    <label for="{{ $id }}" class="ajax-upload-input-wrapper {{ $document?->id ? 'd-none' : 'd-flex' }} w-100" style="cursor: pointer;">
        <div style="border: 1px solid #ddd;" class="d-flex w-100 justify-content-between align-items-center">
            <div class="px-3">{{ __('Choose file to upload') }}</div>
            <div class="btn btn-primary btn--icon-text">
                <span class="pr-2">{{ __("Select File") }}</span>
                <i class="zmdi zmdi-upload"></i>
            </div>
        </div>
        <input class="d-none ajax-upload-input" name="{{ $name }}" type="file" id="{{ $id }}"/>
    </label>

    <div style="border: 1px solid #ddd;" class="ajax-upload-file {{ $document?->id ? 'd-flex' : 'd-none' }} w-100 align-items-center px-3 py-2">
        <div class="d-flex align-items-center">
            <i class="zmdi zmdi-file pr-3" style="font-size:20px;"></i>
        </div>
        <div class="d-flex flex-column  flex-grow-1 justify-content-between align-items-center">
            <div class="d-flex w-100 flex-grow-1 justify-content-between align-items-center">
                <span class="upload-file-name">{{ $document?->file_name ?? '' }}</span>
                <span class="upload-file-size">{{ $document?->human_readable_size }}</span>
            </div>
        </div>
        <div>
            <i data-ajax-upload-remove="{{ $document?->id }}" class="pl-3 zmdi zmdi-close" style="cursor:pointer;"></i>
        </div>
    </div>

    <div style="border: 1px solid #ddd;" class="ajax-upload-progress d-none w-100 align-items-center px-3 py-2">
        <div class="d-flex align-items-center">
            <i class="zmdi zmdi-file pr-3" style="font-size:20px;"></i>
        </div>
        <div class="d-flex  flex-column  flex-grow-1 justify-content-between align-items-center">
            <div class="d-flex w-100 flex-grow-1 justify-content-between align-items-center">
                <span>{{ __('No file chosen') }}</span>
                <span class="progress-percent"></span>
            </div>
            <div class="progress w-100">
                <div class="progress-bar bg-info" role="progressbar"
                     style="width: 50%"
                     aria-valuenow="50"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
        </div>
        <div>
            <i class="pl-3 zmdi zmdi-close"></i>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script>
        $(document).ready(function () {
            $('input.ajax-upload-input').on('change', function () {
                let element = $(this);
                let parent = element.closest('.ajax-upload');

                let modelType = parent.data('model-type');
                let modelId = parent.data('model-id');
                let documentType = parent.data('document-type');
                let fieldName = parent.data('field-name');

                let inputWrapper = parent.find('.ajax-upload-input-wrapper');
                let fileDisplay = parent.find('.ajax-upload-file');
                let progressDisplay = parent.find('.ajax-upload-progress');
                let progressPercent = progressDisplay.find('.progress-percent');
                let progressBar = progressDisplay.find('.progress-bar');

                let fileNameDisplay = fileDisplay.find('.upload-file-name');
                let fileSizeDisplay = fileDisplay.find('.upload-file-size');

                let file = $(this)[0].files[0];
                let formData = new FormData();

                formData.append('file', file);
                formData.append('model_type', modelType);
                formData.append('model_id', modelId);
                formData.append('document_type', documentType);
                formData.append('field', fieldName);

                $.ajax({
                    url: '{{ route(config('paperless.routes.document_store')) }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        let xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function (e) {
                            if (e.lengthComputable) {
                                inputWrapper.addClass('d-none');
                                inputWrapper.removeClass('d-flex');
                                progressDisplay.addClass('d-flex');
                                progressDisplay.removeClass('d-none');

                                let percent = Math.round((e.loaded / e.total) * 100);
                                progressPercent.text(percent + '%');
                                progressBar.css('width', percent + '%');
                            }
                        });
                        return xhr;
                    },
                    success: function (response) {
                        progressDisplay.addClass('d-none');
                        progressDisplay.removeClass('d-flex');
                        fileNameDisplay.html(response.file_name);
                        fileSizeDisplay.html(response.human_readable_size);
                        fileDisplay.addClass('d-flex');
                        fileDisplay.removeClass('d-none');
                        console.log(response);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            });

            $('[data-ajax-upload-remove]').on('click', function () {
                let element = $(this);
                let documentId = element.data('ajax-upload-remove');
                let parent = element.closest('.ajax-upload');
                let inputWrapper = parent.find('.ajax-upload-input-wrapper');
                let fileDisplay = parent.find('.ajax-upload-file');
                let progressDisplay = parent.find('.ajax-upload-progress');
                let deleteUrl = '{{ route('api.documents.store') }}' + '/' + documentId;
                // using $.ajax remvoe the file from the database, call route api.documents.destroy
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    success: function (response) {
                        inputWrapper.addClass('d-flex');
                        inputWrapper.removeClass('d-none');
                        fileDisplay.addClass('d-none');
                        fileDisplay.removeClass('d-flex');
                        progressDisplay.addClass('d-none');
                        progressDisplay.removeClass('d-flex');
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });


            });
        });
    </script>
@endPushOnce
