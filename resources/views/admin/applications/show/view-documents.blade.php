@extends('paperless::admin.applications.applications')

@section('inner-content')

    @php
        $documents_count = $application->getMedia('documents')->count();
    @endphp

    {!! $application->renderRequiredDocumentList() !!}
    {!! $application->renderAdditionalDocumentList() !!}

    @if ($documents_count == 0)
        <div class="card">
            <div class="card-body">
                {{ __('No documents uploaded.') }}
            </div>
        </div>
    @endif
@endsection
