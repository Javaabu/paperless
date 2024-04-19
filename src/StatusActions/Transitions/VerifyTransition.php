<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Verified;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;

class VerifyTransition extends Transition
{
    public function __construct(
        public Application $application,
    ) {
    }

    public function handle(): Application
    {
        $this->application->callServiceFunction('doBeforeMarkingAsVerified');

        $this->application->status = new Verified($this->application);
        $this->application->verifiedBy()->associate(auth()->user());
        $this->application->verified_at = now();
        $this->application->save();

        $this->application->createStatusEvent(
            new Verified($this->application),
            $remarks ?? (new Verified($this->application))->getRemarks()
        );

        $this->application->callServiceFunction('doAfterMarkingAsVerified');
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
