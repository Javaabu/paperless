@foreach($applications as $application)
    @component('admin.components.table-row', [
            'model' => 'applications',
            'model_id' => $application->id,
            'no_checkbox' => ! empty($no_checkbox),
        ])

    @slot('columns')
        <td data-col="{{ __('Reference') }}">
            {!! $application->admin_link !!}
            <div class="table-actions actions">
                <a class="actions__item"><span>{{ __('ID: :id', ['id' => $application->id]) }}</span></a>

                @if($application->trashed())
                    @can('forceDelete', $application)
                        <a class="actions__item delete-link zmdi zmdi-delete" href="#" data-request-url="{{ route('admin.applications.force-delete', $application) }}"
                           data-redirect-url="{{ Request::fullUrl() }}" title="Delete Permanently">
                            <span>{{ __('Delete Permanently') }}</span>
                        </a>
                    @endcan

                    @can('restore', $application)
                        <a class="actions__item restore-link zmdi zmdi-time-restore-setting" href="#" data-post-url="{{ route('admin.applications.restore', $application) }}"
                           data-redirect-url="{{ Request::fullUrl() }}" title="Restore">
                            <span>{{ __('Restore') }}</span>
                        </a>
                    @endcan
                @else
                    @can('view', $application)
                        <a class="actions__item zmdi zmdi-eye" href="{{ route('admin.applications.show', $application) }}" title="View">
                            <span>{{ __('View') }}</span>
                        </a>
                    @endcan

                    @can('update', $application)
                        <a class="actions__item zmdi zmdi-edit" href="{{ route('admin.applications.edit', $application) }}" title="Edit">
                            <span>{{ __('Edit') }}</span>
                        </a>
                    @endcan

                    @can('delete', $application)
                        <a class="actions__item delete-link zmdi zmdi-delete" href="#" data-request-url="{{ route('admin.applications.destroy', $application) }}"
                           data-redirect-url="{{ Request::fullUrl() }}" title="Delete">
                            <span>{{ __('Delete') }}</span>
                        </a>
                    @endcan

                    @if (filled($application->submitted_at))
                        @can('view', $application)
                            <a class="actions__item zmdi zmdi-file-text" href="{{ route('admin.applications.receipt', $application) }}"
                               data-redirect-url="{{ Request::fullUrl() }}" title="Receipt">
                                <span>{{ __('Receipt') }}</span>
                            </a>
                        @endcan
                    @endif
                @endif
            </div>
        </td>
        <td data-col="{{ __('Application Type') }}">{!! $application->applicationType?->admin_link !!}</td>
        <td data-col="{{ __('Applicant') }}">
            {!! $application->applicant?->admin_link !!}
            <div>
                <x-badge color="info">{{ $application->applicant?->getApplicantTypeName() }}</x-badge>
            </div>
        </td>
        @canany(\App\Models\ApplicationType::getAllAssignPermissionList())
            <td data-col="{{ __('Assigned To') }}">{!! $application->assignedTo?->admin_link !!}</td>
        @endcanany
        <td data-col="{{ __('Generated') }}">{!! $application->generated?->admin_link !!}</td>
        <td data-col="{{ __('Submitted Date') }}">{{ format_datetime($application->submitted_at) ?? '—' }}</td>
        <td data-col="{{ __('Progress Status') }}">
            <x-badge color="{{ $application->urgency_color }}">{{ $application->urgency_label }}</x-badge>
        </td>
        <td data-col="{{ __('Payment Status') }}">{{ $application->payment_status }}</td>
        <td data-col="{{ __('Status') }}">
            <x-badge color="{{ $application->status->getColor() }}">{{ $application->status->getLabel() }}</x-badge>
        </td>
    @endslot

    @endcomponent
@endforeach
