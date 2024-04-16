<div class="actions">
    @if(isset($application->id))
        @can('delete', $application)
        <a class="actions__item delete-link zmdi zmdi-delete" href="#"
            data-request-url="{{ route('admin.applications.destroy', $application) }}"
            data-redirect-url="{{ route('admin.applications.index') }}" title="Delete">
            <span>{{ __('Delete') }}</span>
        </a>
        @endcan

        @can('viewLogs', $application)
        <a class="actions__item zmdi zmdi-assignment" href="{{ $application->log_url }}" target="_blank" title="View Logs">
            <span>{{ __('View Logs') }}</span>
        </a>
        @endcan

            @if (request()->routeIs('admin.applications.edit'))
                @can('view', $application)
                    <a class="actions__item zmdi zmdi-eye" href="{{ route('admin.applications.show', $application) }}" title="View">
                        <span>{{ __('View') }}</span>
                    </a>
                @endcan
            @endif

            @if (request()->routeIs('admin.applications.show'))
                @can('update', $application)
                    <a class="actions__item zmdi zmdi-edit" href="{{ route('admin.applications.edit', $application) }}" title="Edit">
                        <span>{{ __('Edit') }}</span>
                    </a>
                @endcan
            @endif
    @endif

    @can('create', App\Models\Application::class)
    <a class="actions__item zmdi zmdi-plus" href="{{ route('admin.applications.create') }}" title="Add New">
        <span>{{ __('Add New') }}</span>
    </a>
    @endcan

    @can('trash', config('paperless.application_model'))
    <a class="{{ config('paperless.application_model')::onlyTrashed()->exists() ? 'indicating' : '' }} actions__item zmdi zmdi-time-restore-setting"
        href="{{ route('admin.applications.trash') }}" title="Trash">
        <span>{{ __('Trash') }}</span>
    </a>
    @endcan

    @can('viewAny', config('paperless.application_model'))
    <a class="actions__item zmdi zmdi-view-list-alt" href="{{ route('admin.applications.index') }}" title="List All">
        <span>{{ __('View All') }}</span>
    </a>
    @endcan
</div>
