@component('admin.components.table', [
        'model' => 'document_types',
        'no_bulk' => ! empty($no_bulk),
        'no_checkbox' => ! empty($no_checkbox),
        'no_pagination' => ! empty($no_pagination),
    ])

    @slot('bulk_form_open')
        {!! Form::open(['url' => route('admin.application-types.document-types.bulk', $application_type), 'method' => 'PUT', 'class' => 'delete-form']) !!}
    @endslot

    @slot('bulk_form')
        @include('admin.application-types.document-types._bulk')
    @endslot

    @slot('titles')
        <th>{{ __('Document Type') }}</th>
        <th>{{ __('Is Required') }}</th>
    @endslot

    @slot('rows')
        @if($document_types->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching application types found.') }}</td>
            </tr>
        @else
            @include('admin.application-types.document-types._list')
        @endif
    @endslot

    @if(empty($no_pagination))
        @slot('pagination')
            {{  $document_types->links('admin.partials.pagination') }}
        @endslot
    @endif
@endcomponent
