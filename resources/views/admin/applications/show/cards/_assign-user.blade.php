<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Assigned Staff') }}</h4>
    </div>
    <div class="card-body">
    {!! Form::model($application, ['method' => 'PATCH', 'route' => ['admin.applications.assign-staff', $application]]) !!}
        <x-admin.input-group label="{{ __('Staff') }}" for="assign_user" stacked>
            <x-admin.selector name="assign_user" :ajax-url="route('api.users.index')" name-field="list_name" />
        </x-admin.input-group>

        <x-admin.input-button class="mt-4"
                              data-confirm="Are you sure you want to reassign the staff responsible for processing this application?"
                              type="submit"
                              color="success"
        >
            <i class="fa-light fa-user-check mr-2"></i>
            <span>{{ __('Reassign Staff') }}</span>
        </x-admin.input-button>
    {!! Form::close() !!}
    </div>
</div>
