@foreach($application_types as $application_type)
    @component('admin.components.table-row', [
            'model' => 'application_types',
            'model_id' => $application_type->id,
            'no_checkbox' => ! empty($no_checkbox),
        ])

    @slot('columns')
        <td data-col="{{ __('Name') }}">
            {!! $application_type->admin_link !!}
            <x-admin.inline-actions :model="$application_type">
                @can('viewStats', $application_type)
                    <a class="actions__item zmdi zmdi-trending-up" href="{{ route('admin.application-types.stats', $application_type) }}" title="Stats">
                        <span>{{ __('Stats') }}</span>
                    </a>
                @endcan
            </x-admin.inline-actions>
        </td>

        <td data-col="{{ __('Document Types') }}">
            <div class="d-flex flex-column align-items-start flex-wrap gap-1">
                @foreach($application_type->documentTypes as $document_type)
                    <x-link-badge
                        :link="$document_type->admin_url"
                        class="mb-1"
                        :color="$document_type->pivot->is_required ? 'success' : 'secondary'">
                        {{ $document_type->name }}
                    </x-link-badge>
                @endforeach
            </div>
        </td>
        <td data-col="{{ __('Services') }}">
            <div class="d-flex flex-column align-items-start flex-wrap gap-1">
                @foreach($application_type->services as $service)
                    <x-link-badge
                        :link="$service->admin_url"
                        :color="$service->pivot->is_applied_automatically ? 'primary' : 'info'"
                        class="mb-1">
                        {{ $service->name }}
                    </x-link-badge>
                @endforeach
            </div>
        </td>
        <td data-col="{{ __('Applications') }}">{{ $application_type->applications_count ?? '0' }}</td>
        <td data-col="{{ __('ETA') }}">{{ $application_type->eta_duration . ' ' . __('days') }}</td>
        <td data-col="{{ __('Alert At') }}">{{ $application_type->alert_duration . ' ' . __('days') }}</td>
        <td data-col="{{ __('Category') }}">{{ $application_type->application_category->getLabel() }}</td>
    @endslot

    @endcomponent
@endforeach
