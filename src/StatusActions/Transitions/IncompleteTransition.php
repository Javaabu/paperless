<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\Events\UpdatedApplicationStatus;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Events\UpdatingApplicationStatus;
use Javaabu\Paperless\StatusActions\Statuses\Incomplete;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;

class IncompleteTransition extends Transition
{
    public function __construct(
        public Application $application,
        public string | null $remarks = null,
    ) {
    }

    public function handle(): Application
    {
        $this->application->callServiceFunction('doBeforeMarkingAsIncomplete');

        UpdatingApplicationStatus::dispatch($this->application);

        $this->application->status = new Incomplete($this->application);
        $this->application->verifiedBy()->associate(auth()->user());
        $this->application->verified_at = now();
        $this->application->eta_at = null;
        $this->application->save();

        $status_event = $this->application->createStatusEvent(
            new Incomplete($this->application),
            $this->remarks ?? (new Incomplete($this->application))->getRemarks()
        );

        $this->application->callServiceFunction('doAfterMarkingAsIncomplete');

        // Give a fresh instance of the application as at this point, things would have changed.
        UpdatedApplicationStatus::dispatch($this->application->fresh(), $status_event);

        return $this->application;
    }

    public function canTransition(): bool
    {
        if ($this->application->status->getValue() != PendingVerification::getMorphClass()) {
            return false;
        }

        if (auth()->user()->can($this->application->applicationType?->getVerifyAnyPermissionAttribute())) {
            return true;
        }

        return auth()->user()->can($this->application->applicationType?->getVerifyPermissionAttribute()) && $this->application->canBeAccessedBy(auth()->user());
    }
}
