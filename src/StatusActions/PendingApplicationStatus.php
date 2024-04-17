<?php

namespace Javaabu\Paperless\StatusActions;

use Javaabu\Auth\User;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\StatusActions\Traits\HasCancelAction;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredFields;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredDocuments;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredAdminFields;

class PendingApplicationStatus extends ApplicationStatusAction
{
    use HasCancelAction;

    protected CheckPresenceOfRequiredAdminFields $check_presence_of_required_admin_fields;

    public function __construct(
        public Application $application,
        ?User $user = null,
    ) {
        parent::__construct($application, $user);
        $this->check_presence_of_required_admin_fields = app(CheckPresenceOfRequiredAdminFields::class);
    }

    public function markAsRejected(?string $remarks = null): Application
    {
        $application_status_enum = config('paperless.enums.application_status');
        $this->canMarkAsRejected();

        $this->application->doBeforeMarkingAsRejected();

        $this->application->status = $application_status_enum::Rejected->value;
        $this->application->verifiedBy()->associate(auth()->user());
        $this->application->verified_at = now();
        $this->application->save();


        $this->application->createStatusEvent(
            $application_status_enum::Rejected->value,
            $remarks ?? $application_status_enum::Rejected->getRemarks()
        );

        $this->application->doAfterMarkingAsRejected();
        return $this->application;
    }

    public function markAsApproved(?string $remarks = null): Application
    {
        $application_status_enum = config('paperless.enums.application_status');
        $this->application->doBeforeApproval();

        if (! $this->check_presence_of_required_admin_fields->handle($this->application)) {
            throw new InvalidOperationException('Cannot approve the application request. Please fill all the required admin fields.');
        }

        $this->application->status = $application_status_enum::Approved;
        $this->application->approvedBy()->associate(auth()->user());
        $this->application->approved_at = now();
        $this->application->save();

        $this->application->doAfterApproval();

        $this->application->createStatusEvent(
            $application_status_enum::Approved->value,
            $remarks ?? $application_status_enum::Approved->getRemarks(),
        );

        return $this->application;
    }
}
