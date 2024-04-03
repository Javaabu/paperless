@foreach($services as $service)

    <x-forms::table.row :model="$service" :no-checkbox="! empty($no_checkbox)">

        <x-forms::table.cell :label="__('Name')">
            {!! $service->admin_link !!}
            <div class="table-actions actions">
                <a class="actions__item"><span>{{ __('ID: :id', ['id' => $service->id]) }}</span></a>

                @can('view', $service)
                    <a class="actions__item zmdi zmdi-eye" href="{{ route('admin.services.show', $service) }}"
                       title="View">
                        <span>{{ __('View') }}</span>
                    </a>
                @endcan

                @can('update', $service)
                    <a class="actions__item zmdi zmdi-edit" href="{{ route('admin.services.edit', $service) }}"
                       title="Edit">
                        <span>{{ __('Edit') }}</span>
                    </a>
                @endcan

                @can('delete', $service)
                    <a class="actions__item delete-link zmdi zmdi-delete" href="#"
                       data-request-url="{{ route('admin.services.destroy', $service) }}"
                       data-redirect-url="{{ Request::fullUrl() }}" title="Delete">
                        <span>{{ __('Delete') }}</span>
                    </a>
                @endcan
            </div>
        </x-forms::table.cell>

        <x-forms::table.cell name="code"/>
        <x-forms::table.cell name="fee">
            {{ format_currency($service->fee) }}
        </x-forms::table.cell>

    </x-forms::table.row>
@endforeach
