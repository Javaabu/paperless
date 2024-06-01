<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Verified;
use Javaabu\Paperless\Events\UpdatedApplicationStatus;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Events\UpdatingApplicationStatus;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;

class UndoVerificationTransition extends Transition
{
    public function __construct(
        public Application $application,
        public string | null $remarks = null,
    ) {
    }

    public function handle(): Application
    {
        $this->application->callServiceFunction('doBeforeUndoVerification');

        UpdatingApplicationStatus::dispatch($this->application);

        $this->application->status = new PendingVerification($this->application);
        $this->application->verifiedBy()->dissociate();
        $this->application->verified_at = null;
        $this->application->save();

        $status_event = $this->application->createStatusEvent(
            new PendingVerification($this->application),
            $this->remarks ?? (new PendingVerification($this->application))->getRemarks()
        );

        $this->application->callServiceFunction('doAfterUndoVerification');

        // Give a fresh instance of the application as at this point, things would have changed.
        UpdatedApplicationStatus::dispatch($this->application->fresh(), $status_event);

        return $this->application;
    }

    public function canTransition(): bool
    {
        if ($this->application->status->getValue() != Verified::getMorphClass()) {
            return false;
        }

        if (auth()->user()->can($this->application->applicationType?->getVerifyAnyPermissionAttribute())) {
            return true;
        }

        return auth()->user()->can($this->application->applicationType?->getVerifyPermissionAttribute()) && $this->application->canBeAccessedBy(auth()->user());
    }
}
