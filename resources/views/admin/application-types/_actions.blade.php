<div class="actions">
    @if(isset($application_type->id))
        @can('viewLogs', $application_type)
        <a class="actions__item zmdi zmdi-assignment" href="{{ $application_type->log_url }}" target="_blank" title="View Logs">
            <span>{{ __('View Logs') }}</span>
        </a>
        @endcan
    @endif

    @can('viewAny', config('paperless.models.application_type'))
    <a class="actions__item zmdi zmdi-view-list-alt" href="{{ route('admin.application-types.index') }}" title="List All">
        <span>{{ __('View All') }}</span>
    </a>
    @endcan
</div>
