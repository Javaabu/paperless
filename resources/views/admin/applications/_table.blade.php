@component('admin.components.table', [
        'model' => 'applications',
        'no_bulk' => ! empty($no_bulk),
        'no_checkbox' => ! empty($no_checkbox),
        'no_pagination' => ! empty($no_pagination),
    ])

    @slot('bulk_form_open')
        {!! Form::open(['route' => 'admin.applications.bulk', 'method' => 'PUT', 'class' => 'delete-form']) !!}
    @endslot

    @slot('bulk_form')
        @include('admin.applications._bulk')
    @endslot

    @slot('titles')
        <th>{{ __('Reference') }}</th>
        <th>{{ __('Application Type') }}</th>
        <th>{{ __('Applicant') }}</th>
        @canany(\App\Models\ApplicationType::getAllAssignPermissionList())
            <th>{{ __('Assigned To') }}</th>
        @endcanany
        <th>{{ __('Generated') }}</th>
        <th>{{ __('Submitted Date') }}</th>
        <th>{{ __('Progress Status') }}</th>
        <th>{{ __('Payment Status') }}</th>
        <th>{{ __('Status') }}</th>
    @endslot

    @slot('rows')
        @if($applications->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching applications found.') }}</td>
            </tr>
        @else
            @include('admin.applications._list')
        @endif
    @endslot

    @if(empty($no_pagination))
        @slot('pagination')
            {{  $applications->links('admin.partials.pagination') }}
        @endslot
    @endif
@endcomponent
