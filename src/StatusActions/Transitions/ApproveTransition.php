<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Approved;
use Javaabu\Paperless\StatusActions\Statuses\Verified;
use Javaabu\Paperless\Domains\Applications\Application;

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
        if ($this->application->status->getValue() != Verified::getMorphClass()) {
            return false;
        }

        if (auth()->user()->can($this->application->applicationType?->getApproveAnyPermissionAttribute())) {
            return true;
        }

        return auth()->user()->can($this->application->applicationType?->getApprovePermissionAttribute()) && $this->application->canBeAccessedBy(auth()->user());
    }
}
