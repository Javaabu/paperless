@component('admin.components.table', [
        'model' => 'application_types',
        'no_bulk' => ! empty($no_bulk),
        'no_checkbox' => ! empty($no_checkbox),
        'no_pagination' => ! empty($no_pagination),
    ])

    @slot('bulk_form_open')
        {!! Form::open(['route' => 'admin.application-types.bulk', 'method' => 'PUT', 'class' => 'delete-form']) !!}
    @endslot

    @slot('bulk_form')
        @include('admin.application-types._bulk')
    @endslot

    @slot('titles')
        <th data-sort-field="name" class="{{ add_sort_class('name') }}">{{ __('Name') }}</th>
        <th>{{ __('Document Types') }}</th>
        <th>{{ __('Services') }}</th>
        <th>{{ __('Applications') }}</th>
        <th>{{ __('ETA') }}</th>
        <th>{{ __('Alert') }}</th>
        <th>{{ __('Category') }}</th>
    @endslot

    @slot('rows')
        @if($application_types->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching application types found.') }}</td>
            </tr>
        @else
            @include('admin.application-types._list')
        @endif
    @endslot

    @if(empty($no_pagination))
        @slot('pagination')
            {{  $application_types->links('admin.partials.pagination') }}
        @endslot
    @endif
@endcomponent
