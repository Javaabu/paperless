<x-paperless::form-container
    id="{{ $getContainerId() }}"
    class="{{ $getContainerClass() }}"
>

    <x-slot:cardHeader>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="card-title">{{ $getHeading() }}</div>
                @if ($getDescription())
                    <div class="card-subtitle">{{ $getDescription() }}</div>
                @endif
            </div>
            @if ($getCanBeRemoved() && ! empty($getContainerId()))
            <div>
                <button data-remove-section="#{{ $getContainerId() }}" class="btn btn-danger">{{ __('Remove') }}</button>
            </div>
            @endif
        </div>
    </x-slot:cardHeader>

    {!! $getChildComponents() !!}

</x-paperless::form-container>

@pushonce('scripts')
    <script>
        $(document).ready(function () {
            $('[data-remove-section]').on('click', function (e) {
                e.preventDefault();
                let targetWrapper = $(this).data('remove-section');
                $(targetWrapper).slideUp(500, function () {
                    $(targetWrapper).remove();
                });
            });
        });
    </script>
@endpushonce
