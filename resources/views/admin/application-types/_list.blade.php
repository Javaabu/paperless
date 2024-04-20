@foreach($application_types as $application_type)
    <x-forms::table.row :model="$application_type" :no-checkbox="! empty($no_checkbox)">

        <x-forms::table.cell :label="__('Name')">
            {!! $application_type->admin_link !!}
            <div class="table-actions actions">
                <a class="actions__item"><span>{{ __('ID: :id', ['id' => $application_type->id]) }}</span></a>

                @can('view', $application_type)
                    <a class="actions__item zmdi zmdi-eye"
                       href="{{ route('admin.application-types.show', $application_type) }}"
                       title="View">
                        <span>{{ __('View') }}</span>
                    </a>
                @endcan

                @can('update', $application_type)
                    <a class="actions__item zmdi zmdi-edit"
                       href="{{ route('admin.application-types.edit', $application_type) }}"
                       title="Edit">
                        <span>{{ __('Edit') }}</span>
                    </a>
                @endcan
            </div>
        </x-forms::table.cell>

        <x-forms::table.cell name="document_types">
            @foreach($application_type->documentTypes as $document_type)
                <x-paperless::link-badge
                    :link="$document_type->admin_url"
                    class="mb-1"
                    :color="$document_type->pivot->is_required ? 'success' : 'secondary'">
                    {{ $document_type->name }}
                </x-paperless::link-badge>
            @endforeach
        </x-forms::table.cell>

        <x-forms::table.cell name="services">
            <div class="d-flex flex-column align-items-start flex-wrap gap-1">
                @foreach($application_type->services as $service)
                    <x-paperless::link-badge
                        :link="$service->admin_url"
                        :color="$service->pivot->is_applied_automatically ? 'primary' : 'info'"
                        class="mb-1">
                        {{ $service->name }}
                    </x-paperless::link-badge>
                @endforeach
            </div>
        </x-forms::table.cell>

        <x-forms::table.cell name="applications">
            {{ $application_type->applications_count ?? '0' }}
        </x-forms::table.cell>

        <x-forms::table.cell name="eta">
            {{ $application_type->eta_duration . ' ' . __('days') }}
        </x-forms::table.cell>

        <x-forms::table.cell name="category">
            {{ $application_type->application_category->getApplicationTypeCategoryLabel()  }}
        </x-forms::table.cell>

    </x-forms::table.row>
@endforeach
