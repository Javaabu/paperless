<div class="actions">
    @if(isset($document_type->id))
        @can('delete', $document_type)
            <a class="actions__item delete-link zmdi zmdi-delete" href="#"
               data-request-url="{{ route('admin.document-types.destroy', $document_type) }}"
               data-redirect-url="{{ route('admin.document-types.index') }}" title="Delete">
                <span>{{ __('Delete') }}</span>
            </a>
        @endcan

        @can('viewLogs', $document_type)
            <a class="actions__item zmdi zmdi-assignment" href="{{ $document_type->log_url }}" target="_blank" title="View Logs">
                <span>{{ __('View Logs') }}</span>
            </a>
        @endcan
    @endif

    @can('create', config('paperless.models.service'))
        <a class="actions__item zmdi zmdi-plus" href="{{ route('admin.document-types.create') }}" title="Add New">
            <span>{{ __('Add New') }}</span>
        </a>
    @endcan

    @can('trash', config('paperless.models.service'))
        <a class="{{ config('paperless.models.service')::onlyTrashed()->exists() ? 'indicating' : '' }} actions__item zmdi zmdi-time-restore-setting"
           href="{{ route('admin.document-types.trash') }}" title="Trash">
            <span>{{ __('Trash') }}</span>
        </a>
    @endcan

    @can('viewAny', config('paperless.models.service'))
        <a class="actions__item zmdi zmdi-view-list-alt" href="{{ route('admin.document-types.index') }}" title="List All">
            <span>{{ __('View All') }}</span>
        </a>
    @endcan
</div>
