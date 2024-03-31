@props([
    'label',
    'danger' => false
])

<div class="row">
    <div class="col-md-3">
        {{ $getLabel() }} {{ $isMarkedAsRequired() ? '*' : '' }}
    </div>
    @if ($getSize())
        <div style="border: 1px solid #ddd;"
             class="col-md-9 d-flex w-100 align-items-center justify-content-between px-3 py-2">
            <div class="d-flex align-items-center">
                <i class="zmdi zmdi-file pr-3" style="font-size:20px;"></i>
                <span class="upload-file-name">{{ $getFileName() }}</span>
            </div>
            <a class="inline-block" target="_blank" href="{{ $getDownloadLink() }}">
                <span class="upload-file-size">{{ $getSize() }}</span>
                <i class="pl-2 fa-regular fa-arrow-down-to-line" style="cursor:pointer;"></i>
            </a>
        </div>
    @else
        <div class="text-muted">
            {{ __('No document uploaded') }}
        </div>
    @endif
</div>
