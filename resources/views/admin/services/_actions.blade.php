<div class="actions">
    @if(isset($service->id))
        @can('delete', $service)
        <a class="actions__item delete-link zmdi zmdi-delete" href="#"
            data-request-url="{{ route('admin.services.destroy', $service) }}"
            data-redirect-url="{{ route('admin.services.index') }}" title="Delete">
            <span>{{ __('Delete') }}</span>
        </a>
        @endcan

        @can('viewLogs', $service)
        <a class="actions__item zmdi zmdi-assignment" href="{{ $service->log_url }}" target="_blank" title="View Logs">
            <span>{{ __('View Logs') }}</span>
        </a>
        @endcan
    @endif

    @can('create', config('paperless.models.service'))
    <a class="actions__item zmdi zmdi-plus" href="{{ route('admin.services.create') }}" title="Add New">
        <span>{{ __('Add New') }}</span>
    </a>
    @endcan

    @can('trash', config('paperless.models.service'))
    <a class="{{ config('paperless.models.service')::onlyTrashed()->exists() ? 'indicating' : '' }} actions__item zmdi zmdi-time-restore-setting"
        href="{{ route('admin.services.trash') }}" title="Trash">
        <span>{{ __('Trash') }}</span>
    </a>
    @endcan

    @can('viewAny', config('paperless.models.service'))
    <a class="actions__item zmdi zmdi-view-list-alt" href="{{ route('admin.services.index') }}" title="List All">
        <span>{{ __('View All') }}</span>
    </a>
    @endcan
</div>
