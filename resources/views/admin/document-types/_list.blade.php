@foreach($document_types as $document_type)
    <x-forms::table.row :model="$document_type" :no-checkbox="! empty($no_checkbox)">
        <x-forms::table.cell :label="__('Name')">
            {!! $document_type->admin_link !!}
            <div class="table-actions actions">
                <a class="actions__item"><span>{{ __('ID: :id', ['id' => $document_type->id]) }}</span></a>

                @if($document_type->trashed())
                    @can('forceDelete', $document_type)
                        <a class="actions__item delete-link zmdi zmdi-delete" href="#" data-request-url="{{ route('admin.document-types.force-delete', $document_type) }}"
                           data-redirect-url="{{ Request::fullUrl() }}" title="Delete Permanently">
                            <span>{{ __('Delete Permanently') }}</span>
                        </a>
                    @endcan

                    @can('restore', $document_type)
                        <a class="actions__item restore-link zmdi zmdi-time-restore-setting" href="#" data-post-url="{{ route('admin.document-types.restore', $document_type) }}"
                           data-redirect-url="{{ Request::fullUrl() }}" title="Restore">
                            <span>{{ __('Restore') }}</span>
                        </a>
                    @endcan
                @else
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
                @endif
            </div>
        </x-forms::table.cell>

        <x-forms::table.cell name="slug"/>
        <x-forms::table.cell name="description"/>
    </x-forms::table.row>

@endforeach
