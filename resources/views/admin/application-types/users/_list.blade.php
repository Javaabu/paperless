@foreach($users as $user)
    @component('admin.components.table-row', [
            'model' => 'assigned_users',
            'model_id' => $user->pivot->id,
            'no_checkbox' => ! empty($no_checkbox),
        ])

        @slot('columns')
            <td data-col="{{ __('User') }}">
                {!! $user->admin_link !!}
                <x-admin.nested-inline-actions
                    :actions="[
                        'delete' => route('admin.application-types.assigned-users.destroy', [$application_type, $user]),
                    ]"
                    :parent-model="$application_type"
                    :id="$user->pivot->id" />
            </td>
            <td data-col="{{ __('Is Active') }}">
                <x-badge :color="$user->pivot->is_active ? 'success' : 'secondary'">
                    {{ $user->pivot->is_active ? __('Yes') : __('No') }}
                </x-badge>
            </td>
            <td data-col="{{ __('Assigned Applications') }}">
               {{ $user->assigned_applications_count }}
            </td>

            <td data-col="{{ __('Pending Applications') }}">
                {{ $user->pending_applications_count }}
            </td>
        @endslot
     @endcomponent
@endforeach
