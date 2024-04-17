@props([
    'display' => false,
])

<div style="border: 1px solid #ddd;"
     class="ajax-upload-progress {{ $display ? 'd-flex' : 'd-none' }} w-100 align-items-center px-3 py-2">
    <div class="d-flex align-items-center">
        <i class="zmdi zmdi-file pr-3" style="font-size:20px;"></i>
    </div>
    <div class="d-flex  flex-column  flex-grow-1 justify-content-between align-items-center">
        <div class="d-flex w-100 flex-grow-1 justify-content-between align-items-center">
            <span class="progress-filename">{{ __('No file chosen') }}</span>
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
