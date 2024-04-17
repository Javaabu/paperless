@if ($required_documents->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <div class="card-title">{{ __('Documents') }}</div>
            <div
                class="card-subtitle">{{ __('Upload corresponding documents. Documents should not be larger than :size MB', ['size' => 2]) }}</div>
        </div>
        <div class="card-body">
            @foreach ($required_documents as $required_document)
                @php
                    $document = $documents->where('document_type_id', $required_document->id)->first();
                @endphp
                <x-paperless::input-group :label="$required_document->name"
                                          :required="$required_document->pivot->is_required">
                    <x-paperless::ajax-file-upload :model="$application"
                                                  :document="$document"
                                                  :document-type="$required_document"
                                                  :name="'documents[' . $required_document->id . ']'"/>
                </x-paperless::input-group>
            @endforeach
        </div>
    </div>
@endif
