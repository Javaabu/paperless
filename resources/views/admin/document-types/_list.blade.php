@foreach($document_types as $document_type)
    <x-forms::table.row :model="$document_type" :no-checkbox="! empty($no_checkbox)">
        <x-forms::table.cell :label="__('Name')">
            {!! $document_type->admin_link !!}
            <div class="table-actions actions">
                <a class="actions__item"><span>{{ __('ID: :id', ['id' => $document_type->id]) }}</span></a>

                @can('view', $document_type)
                    <a class="actions__item zmdi zmdi-eye"
                       href="{{ route('admin.document-types.show', $document_type) }}"
                       title="View">
                        <span>{{ __('View') }}</span>
                    </a>
                @endcan

                @can('update', $document_type)
                    <a class="actions__item zmdi zmdi-edit"
                       href="{{ route('admin.document-types.edit', $document_type) }}"
                       title="Edit">
                        <span>{{ __('Edit') }}</span>
                    </a>
                @endcan

                @can('delete', $document_type)
                    <a class="actions__item delete-link zmdi zmdi-delete" href="#"
                       data-request-url="{{ route('admin.document-types.destroy', $document_type) }}"
                       data-redirect-url="{{ Request::fullUrl() }}" title="Delete">
                        <span>{{ __('Delete') }}</span>
                    </a>
                @endcan
            </div>
        </x-forms::table.cell>

        <x-forms::table.cell name="slug"/>
        <x-forms::table.cell name="description"/>
    </x-forms::table.row>

@endforeach
