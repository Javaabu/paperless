<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Approved;
use Javaabu\Paperless\StatusActions\Statuses\Verified;
use Javaabu\Paperless\Events\UpdatedApplicationStatus;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Events\UpdatingApplicationStatus;

class ApproveTransition extends Transition
{
    public function __construct(
        public Application $application,
        public string | null $remarks = null,
    ) {
    }

    public function handle(): Application
    {
        $this->application->callServiceFunction('doBeforeApproval');

        UpdatingApplicationStatus::dispatch($this->application);

        $this->application->status = new Approved($this->application);
        $this->application->approvedBy()->associate(auth()->user());
        $this->application->approved_at = now();
        $this->application->save();

        $status_event = $this->application->createStatusEvent(
            new Approved($this->application),
            $this->remarks ?? (new Approved($this->application))->getRemarks()
        );

        $this->application->callServiceFunction('doAfterApproval');

        // Give a fresh instance of the application as at this point, things would have changed.
        UpdatedApplicationStatus::dispatch($this->application->fresh(), $status_event);

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
