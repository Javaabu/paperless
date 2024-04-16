
{!! Form::model($application, ['method' => 'PATCH', 'route' => ['admin.applications.admin-section-update', [$application, $section]]]) !!}
<div class="card">
    <div class="card-header">
        <div class="card-title">{{ $section->name }}</div>
        <div class="card-subtitle">{{ $section->description }}</div>
    </div>
    <div class="card-body">
        {!! $section->render($application->applicant, $section_form_inputs, false) !!}

        <div>
            <x-admin.input-button
                data-confirm="Are you sure you want to update the application information?"
                type="submit"
                color="success">
                <i class="fas fa-save mr-2"></i>
                {{ __('Update Application') }}
            </x-admin.input-button>
        </div>
    </div>
</div>
{!! Form::close() !!}
