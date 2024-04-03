@component('admin.components.table', [
        'model' => 'assigned_users',
        'no_bulk' => ! empty($no_bulk),
        'no_checkbox' => ! empty($no_checkbox),
        'no_pagination' => ! empty($no_pagination),
    ])

    @slot('bulk_form_open')
        {!! Form::open(['url' => route('admin.application-types.assigned-users.bulk', $application_type), 'method' => 'PUT', 'class' => 'delete-form']) !!}
    @endslot

    @slot('bulk_form')
        @include('admin.application-types.users._bulk')
    @endslot

    @slot('titles')
        <th>{{ __('Assigned User') }}</th>
        <th>{{ __('Is Active') }}</th>
        <th>{{ __('Assigned Applications') }}</th>
        <th>{{ __('Pending Applications') }}</th>
    @endslot

    @slot('rows')
        @if($users->isEmpty())
            <tr>
                <td colspan="{{ ! empty($no_checkbox) ? 1 : 2 }}">{{ __('No matching users found.') }}</td>
            </tr>
        @else
            @include('admin.application-types.users._list')
        @endif
    @endslot

    @if(empty($no_pagination))
        @slot('pagination')
            {{  $users->links('admin.partials.pagination') }}
        @endslot
    @endif
@endcomponent
