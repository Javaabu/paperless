<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use App\Application\Enums\ApplicationStatuses;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;
use Javaabu\Paperless\StatusActions\Statuses\Approved;
use Javaabu\Paperless\StatusActions\Statuses\Rejected;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredFields;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredDocuments;

class ApproveTransition extends Transition
{

    public function __construct(
        public Application $application,
    ) {
    }

    public function handle(): Application
    {
        $this->application->doBeforeApproval();

        $this->application->status = new Approved($this->application);
        $this->application->approvedBy()->associate(auth()->user());
        $this->application->approved_at = now();
        $this->application->save();

        $this->application->createStatusEvent(
            new Approved($this->application),
            $remarks ?? (new Approved($this->application))->getRemarks()
        );

        $this->application->doAfterApproval();
        return $this->application;
    }

    public function canTransition(): bool
    {
        if ($this->application->status->getValue() != PendingVerification::getMorphClass()) {
            return false;
        }

        if (auth()->user()->can($this->application->applicationType?->getApproveAnyPermissionAttribute())) {
            return true;
        }

        return auth()->user()->can($this->application->applicationType?->getApprovePermissionAttribute()) && $this->application->canBeAccessedBy(auth()->user());
    }
}
