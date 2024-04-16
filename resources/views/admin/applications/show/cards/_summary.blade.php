<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <x-admin.info :label="__('Requested Service')">{!! $application->applicationType?->admin_link !!}</x-admin.info>
                <x-admin.info :label="__('Application Reference Number')">{{ $application->formatted_id }}</x-admin.info>
                <x-admin.info :label="__('Applicant')">
                    @if ($application->applicant_type == \App\Helpers\Enums\EntityTypeEnums::Individual->value)
                        <x-badge>{{ App\Helpers\Enums\EntityTypeEnums::Individual->getlabel() }}</x-badge>
                    @else
                        <x-badge>{{ $application->applicant?->entityType?->name }}</x-badge>
                    @endif
                    <span class="mr-2">{!! $application->applicant?->admin_link !!}</span>
                </x-admin.info>
                <x-admin.info :label="__('Application Submission')">
                    @if ($application->public_user_id)
                        <x-badge color="light">{!! $application->publicUser?->admin_link !!}</x-badge>
                    @else
                        <x-badge color="secondary">{{ __('Over The Counter') }}</x-badge>
                    @endif
                    <span class="ml-2"> - {{ format_datetime($application->submitted_at) ?? '⸺' }}</span>
                </x-admin.info>
                <x-admin.info :label="__('Process Application Before')">{{ format_datetime($application->eta_at) ?? '⸺' }}</x-admin.info>
                <x-admin.info :label="__('Supervisor Alert On')">{{ format_datetime($application->alert_at) ?? '⸺' }}</x-admin.info>
                <x-admin.info :label="__('Urgency')">
                    @if ($application->eta_at)
                        <x-badge :color="$application->urgency_color">{{ $application->urgency_label }}</x-badge>
                    @else
                        ⸺
                    @endif
                </x-admin.info>
            </div>
            <div class="col-md-6">
                <x-admin.info :label="__('Assigned Staff')">{!! $application->assignedTo?->admin_link ?? '⸺' !!}</x-admin.info>

                @if ($application->verified_at)
                <x-admin.info :label="__('Verification')">{!! $application->verifiedBy?->admin_link !!} - {{ format_datetime($application->verified_at) }}</x-admin.info>
                @endif

                @if ($application->approved_at)
                    <x-admin.info :label="__('Approval')">{!! $application->approvedBy?->admin_link !!} - {{ format_datetime($application->approved_at) }}</x-admin.info>
                @endif

                <x-admin.info :label="__('Latest Remarks')">{{ $application->latest_remarks ?? '⸺' }}</x-admin.info>
                @if ($application->related_type)
                    <x-admin.info :label="$application->related_type_label">{!! $application->related?->admin_link !!}</x-admin.info>
                @endif
                @if ($application->generated_type)
                    <x-admin.info :label="$application->generated_type_label">{!! $application->generated?->admin_link !!}</x-admin.info>
                @endif
                <x-admin.info :label="__('Status')">
                    <x-badge :color="$application->status->getColor()">{{ $application->status->getLabel() }}</x-badge>
                </x-admin.info>
            </div>
        </div>


    </div>
</div>
