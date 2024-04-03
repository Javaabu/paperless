@component('admin.components.table', [
        'model' => 'services',
        'no_bulk' => ! empty($no_bulk),
        'no_checkbox' => ! empty($no_checkbox),
        'no_pagination' => ! empty($no_pagination),
    ])

    @slot('bulk_form_open')
        {!! Form::open(['url' => route('admin.application-types.services.bulk', $application_type), 'method' => 'PUT', 'class' => 'delete-form']) !!}
    @endslot

    @slot('bulk_form')
        @include('admin.application-types.services._bulk')
    @endslot

    @slot('titles')
        <th>{{ __('Service') }}</th>
        <th>{{ __('Amount') }}</th>
        <th>{{ __('Is Applied Automatically') }}</th>
    @endslot

    @slot('rows')
        @if($services->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching application types found.') }}</td>
            </tr>
        @else
            @include('admin.application-types.services._list')
        @endif
    @endslot

    @if(empty($no_pagination))
        @slot('pagination')
            {{  $services->links('admin.partials.pagination') }}
        @endslot
    @endif
@endcomponent
