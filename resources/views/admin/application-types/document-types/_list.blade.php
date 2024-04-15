@foreach($document_types as $document_type)
    <x-forms::table.row :model="$document_type" :no-checkbox="! empty($no_checkbox)">

        <x-forms::table.cell :label="__('Name')">
            {!! $document_type->admin_link !!}
            <div class="table-actions actions">
                <a class="actions__item"><span>{{ __('ID: :id', ['id' => $application_type->id]) }}</span></a>

            </div>
        </x-forms::table.cell>

        <x-forms::table.cell name="is_required">
            <span class="badge {{ $document_type->pivot->is_required ? 'badge-success' : 'badge-secondary' }}">
                {{ $document_type->pivot->is_required ? __('Yes') : __('No') }}
            </span>
        </x-forms::table.cell>
    </x-forms::table.row>
@endforeach
