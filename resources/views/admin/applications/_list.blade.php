@foreach($applications as $application)

    <x-forms::table.row :model="$application" :no-checkbox="! empty($no_checkbox)">

        <x-forms::table.cell :label="__('Reference')">
            {!! $application->admin_link !!}
            <div class="table-actions actions">
                <a class="actions__item"><span>{{ __('ID: :id', ['id' => $application->id]) }}</span></a>

                @can('view', $application)
                    <a class="actions__item zmdi zmdi-eye" href="{{ route('admin.applications.show', $application) }}"
                       title="View">
                        <span>{{ __('View') }}</span>
                    </a>
                @endcan

                @can('update', $application)
                    <a class="actions__item zmdi zmdi-edit" href="{{ route('admin.applications.edit', $application) }}"
                       title="Edit">
                        <span>{{ __('Edit') }}</span>
                    </a>
                @endcan

                @can('delete', $application)
                    <a class="actions__item delete-link zmdi zmdi-delete" href="#"
                       data-request-url="{{ route('admin.applications.destroy', $application) }}"
                       data-redirect-url="{{ Request::fullUrl() }}" title="Delete">
                        <span>{{ __('Delete') }}</span>
                    </a>
                @endcan
            </div>
        </x-forms::table.cell>

        <x-forms::table.cell name="application_type">
            {!! $application->applicationType?->admin_link !!}
        </x-forms::table.cell>

        <x-forms::table.cell name="applicant">
            {!! $application->applicant?->admin_link !!}
        </x-forms::table.cell>

        <x-forms::table.cell name="generated">
            {!! $application->generated?->admin_link !!}
        </x-forms::table.cell>

        <x-forms::table.cell name="submitted_at">
            {{ $application->submitted_at?->format('d M Y H:i') }}
        </x-forms::table.cell>

        <x-forms::table.cell name="status">
            <x-paperless::badge :color="$application->status->getColor()" :label="$application->status->getLabel()" />
        </x-forms::table.cell>
    </x-forms::table.row>
@endforeach
