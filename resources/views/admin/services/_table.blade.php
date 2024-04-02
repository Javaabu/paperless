@component('admin.components.table', [
        'model' => 'services',
        'no_bulk' => ! empty($no_bulk),
        'no_checkbox' => ! empty($no_checkbox),
        'no_pagination' => ! empty($no_pagination),
    ])

    @slot('bulk_form_open')
        {!! Form::open(['route' => 'admin.services.bulk', 'method' => 'PUT', 'class' => 'delete-form']) !!}
    @endslot

    @slot('bulk_form')
        @include('admin.services._bulk')
    @endslot

    @slot('titles')
        <th data-sort-field="name" class="{{ add_sort_class('name') }}">{{ __('Name') }}</th>
        <th>{{ __('Code') }}</th>
        <th>{{ __('Fee') }}</th>
    @endslot

    @slot('rows')
        @if($services->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching services found.') }}</td>
            </tr>
        @else
            @include('admin.services._list')
        @endif
    @endslot

    @if(empty($no_pagination))
        @slot('pagination')
            {{  $services->links('admin.partials.pagination') }}
        @endslot
    @endif
@endcomponent
