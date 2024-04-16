@php
    $additional_documents = $application->getMedia('documents')->whereNull('document_type_id')->sortByDesc('created_at');
    $model_type = $application->getMorphClass();
    $model_id = $application->id;
@endphp

<div class="card">
    <div class="card-header">
        <div class="card-title">{{ __('Additional Documents') }}</div>
        <div class="card-subtitle">
            {{ __('You may upload any additional documents here.') }}
            {{--                @if($max = get_setting('max_num_files'))--}}
            {{--                    {{ __('You can upload up to maximum :num additional :type.', ['num' => $max, 'type' => Str::plural('document', $max)]) }}--}}
            {{--                @endif--}}
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex additional-file-form-wrapper"
             data-model-type="{{ $model_type }}"
             data-model-id="{{ $model_id }}"
        >
            <div class="row mb-0 flex-grow-1">
                <div class="col-md-6">
                    <x-admin.input-group for="file_name" :label="__('File Name')" stacked>
                        <x-admin.input-text name="file_name"/>
                    </x-admin.input-group>
                </div>
                <div class="col-md-6">
                    <x-admin.input-group for="file" :label="__('File to Upload')" stacked>
                        <x-admin.file-input name="file"/>
                    </x-admin.input-group>
                </div>
            </div>
            <div class="ml-4 d-flex align-items-end">
                <button type="button" data-add-additional-file class="btn btn-primary btn--raised" id="add-document">
                    <i class="zmdi zmdi-plus"></i> {{ __('Add File') }}
                </button>
            </div>
        </div>
        <div class="mt-2 text-danger" style="font-size:0.9rem;">
            <ul class="list-unstyled upload-errors"></ul>
        </div>
        <hr>
        <div class="additional-files-wrapper">
            @forelse($additional_documents as $additional_document)
                <x-admin.input-group :label="$additional_document->name" data-ajax-upload-wrapper>
                    <x-admin.ajax-additional-file-upload
                        model-type="{{ $application->getMorphClass() }}"
                        model-id="{{ $application->id }}"
                        target-wrapper="data-ajax-upload-wrapper"
                        :document="$additional_document" />
                </x-admin.input-group>
            @empty
            <div id="no-additional-documents">
                {{ __('No additional documents uploaded.') }}
            </div>
            @endforelse
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script>
        $(document).ready(function () {

            $('[data-add-additional-file]').on('click', function () {
                let element = $(this);
                let parentElement = element.closest('.additional-file-form-wrapper');
                let noFilesElement = $('#no-additional-documents');
                let fileInput = parentElement.find('[name="file"]');
                let fileNameDisplay = parentElement.find('.fileinput-filename').first();
                let fileNameInput = parentElement.find('[name="file_name"]');
                let fileName = fileNameInput.val();
                let file = fileInput[0].files[0];
                let modelType = parentElement.data('model-type');
                let modelId = parentElement.data('model-id');
                let uploadErrors = $('.upload-errors').first();
                uploadErrors.html('');

                let fileWrapper = $('.additional-files-wrapper');
                let progressWrapper = `
                    <x-admin.input-group label="Uploading" data-additional-file-upload-progress>
                        <x-fileupload-progress display="true"/>
                    </x-admin.input-group>
                `;

                let uploadedElement = `
                    <x-admin.input-group label="Temporary Name" data-additional-file-uploaded data-ajax-upload-wrapper>
                        <x-admin.ajax-additional-file-upload
                                                model-type="{{ $application->getMorphClass() }}"
                                                model-id="{{ $application->id }}"
                                                target-wrapper="data-ajax-upload-wrapper" />
                    </x-admin.input-group>
                `;

                if (!file) {
                    return;
                }

                let formData = new FormData();
                formData.append('file', file);
                formData.append('name', fileName);
                formData.append('model_type', modelType);
                formData.append('model_id', modelId);


                let url = '{{ route('api.documents.store') }}';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function () {
                        let xhr = new window.XMLHttpRequest();
                        fileWrapper.prepend(progressWrapper);
                        let progressElement = fileWrapper.find('[data-additional-file-upload-progress]');
                        let progressElementLabel = progressElement.find('label');
                        let progressFilename = progressElement.find('.progress-filename');
                        let progressPercent = progressElement.find('.progress-percent');
                        let progressBar = progressElement.find('.progress-bar');

                        progressElementLabel.text(fileName);
                        progressFilename.text(file.name);

                        xhr.upload.addEventListener('progress', function (e) {
                            if (e.lengthComputable) {
                                let percent = Math.round((e.loaded / e.total) * 100);
                                progressPercent.text(percent + '%');
                                progressBar.css('width', percent + '%');
                            }
                        });
                        return xhr;
                    },
                    success: function (response) {
                        let progressElement = fileWrapper.find('[data-additional-file-upload-progress]');
                        progressElement.remove();
                        noFilesElement.remove();
                        fileWrapper.prepend(uploadedElement);
                        let newlyUploadedElement = fileWrapper.find('[data-additional-file-uploaded]').first();
                        let newlyUploadedElementLabel = newlyUploadedElement.find('label');
                        let newlyUploadedElementFilename = newlyUploadedElement.find('.upload-file-name');
                        let newlyUploadedElementSize = newlyUploadedElement.find('.upload-file-size');
                        let newlyUploadedElementRemove = newlyUploadedElement.find('[data-ajax-additional-upload-remove]');

                        newlyUploadedElementLabel.text(fileName);
                        newlyUploadedElementFilename.text(response.file_name);
                        newlyUploadedElementSize.text(response.human_readable_size);
                        newlyUploadedElementRemove.data('ajax-additional-upload-remove', response.id);

                        fileNameInput.val("");
                        fileInput.val("");
                        fileNameDisplay.text("");
                    },
                    error: function (response) {
                        let progressElement = fileWrapper.find('[data-additional-file-upload-progress]');
                        progressElement.remove();

                        let errors = response.responseJSON.errors;
                        let errorList = '';
                        for (let key in errors) {
                            errorList += '<li>' + errors[key] + '</li>';
                        }

                        uploadErrors.html(errorList);
                        fileInput.val("");
                        fileNameDisplay.text("");
                    }
                });

            });
        });
    </script>
@endPushOnce
