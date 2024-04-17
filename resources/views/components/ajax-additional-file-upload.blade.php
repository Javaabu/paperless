@props([
    'modelType',
    'modelId',
    'document' => null,
    'targetWrapper' => '',
    'id' => null,
    'documentType' => null,
])

<div class="d-flex w-100 ajax-additional-upload"
    data-model-type="{{ $modelType }}"
    data-model-id="{{ $modelId }}"
    data-document-type="{{ $documentType?->id }}"
     data-target-wrapper="{{ $targetWrapper }}"
>
    <div style="border: 1px solid #ddd;" class="ajax-upload-file d-flex w-100 align-items-center px-3 py-2">
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
            <i data-ajax-additional-upload-remove="{{ $document?->id }}" class="pl-3 zmdi zmdi-close" style="cursor:pointer;"></i>
        </div>
    </div>
</div>

@pushOnce('scripts')
    <script>
        $(document).ready(function () {

            $('.additional-files-wrapper').on('click', '[data-ajax-additional-upload-remove]', function () {
                let element = $(this);
                let documentId = element.data('ajax-additional-upload-remove');
                let parent = element.closest('.ajax-additional-upload');
                let targetWrapper = parent.data('target-wrapper');
                let wrapper = parent.closest('[' + targetWrapper + ']');

                let deleteUrl = '{{ route('api.documents.store') }}' + '/' + documentId;

                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    success: function (response) {
                        wrapper.remove();
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });

            });
        });
    </script>
@endPushOnce
