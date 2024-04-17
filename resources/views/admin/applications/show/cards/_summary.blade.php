<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <x-forms::text-entry :label="__('Requested Service')" :inline="true">
                    {!! $application->applicationType?->admin_link !!}
                </x-forms::text-entry>
                <x-forms::text-entry :label="__('Application Reference Number')" :inline="true">
                    {{ $application->formatted_id }}
                </x-forms::text-entry>
                <x-forms::text-entry :label="__('Applicant')" :inline="true">
                    <span class="mr-2">{!! $application->applicant?->admin_link !!}</span>
                </x-forms::text-entry>
                <x-forms::text-entry :label="__('Application Submission')" :inline="true">
                    @if ($application->public_user_id)
                        <x-paperless::badge color="light">{!! $application->publicUser?->admin_link !!}</x-paperless::badge>
                    @else
                        <x-paperless::badge color="secondary">{{ __('Over The Counter') }}</x-paperless::badge>
                    @endif
                    <span class="ml-2"> - {{ $application->submitted_at?->format('d M Y H:i') ?? '⸺' }}</span>
                </x-forms::text-entry>
                <x-forms::text-entry :label="__('Process Application Before')" :inline="true">
                    {{ $application->eta_at?->format('d M Y H:i') ?? '⸺' }}
                </x-forms::text-entry>

            </div>
            <div class="col-md-6">

                <x-forms::text-entry :label="__('Verification')" :inline="true">
                    {!! $application->verifiedBy?->admin_link !!} - {{ $application->verified_at?->format('d M Y H:i') ?? '⸺' }}
                </x-forms::text-entry>

                <x-forms::text-entry :label="__('Approval')" :inline="true">
                    {!! $application->approvedBy?->admin_link !!} - {{ $application->approved_at?->format('d M Y H:i') ?? '⸺' }}
                </x-forms::text-entry>

                <x-forms::text-entry :label="__('Latest Remarks')" :inline="true">
                    {{ $application->latest_remarks ?? '⸺' }}
                </x-forms::text-entry>

                @if ($application->related_type)
                    <x-forms::text-entry :label="$application->related_type_label" :inline="true">
                        {!! $application->related?->admin_link !!}
                    </x-forms::text-entry>
                @endif

                @if ($application->generated_type)
                    <x-forms::text-entry :label="$application->generated_type_label" :inline="true">
                        {!! $application->generated?->admin_link !!}
                    </x-forms::text-entry>
                @endif

                <x-forms::text-entry :label="__('Status')" :inline="true">
                    <x-paperless::badge :color="$application->status->getColor()">{{ $application->status->getLabel() }}</x-paperless::badge>
                </x-forms::text-entry>

            </div>
        </div>


    </div>
</div>
