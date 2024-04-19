<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Draft;
use Javaabu\Paperless\StatusActions\Statuses\Incomplete;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;
use Javaabu\Paperless\StatusActions\Statuses\Approved;
use Javaabu\Paperless\StatusActions\Statuses\Rejected;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredFields;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredDocuments;

class UndoRejectionTransition extends Transition
{

    public function __construct(
        public Application $application,
    ) {
    }

    public function handle(): Application
    {
        $this->application->doBeforeUndoRejection();

        $application_eta_days = $this->application?->applicationType?->eta_duration ?? 0;
        $this->application->status = new PendingVerification($this->application);
        $this->application->verifiedBy()->dissociate();
        $this->application->verified_at = null;
        $this->application->eta_at = now()->addDays($application_eta_days);
        $this->application->save();

        $this->application->createStatusEvent(
            new PendingVerification($this->application),
            $remarks ?? (new PendingVerification($this->application))->getRemarks()
        );

        $this->application->doAfterUndoRejection();
        return $this->application;
    }

    public function canTransition(): bool
    {
        if ($this->application->status->getValue() != Rejected::getMorphClass()) {
            return false;
        }

        if (auth()->user()->can($this->application->applicationType?->getVerifyAnyPermissionAttribute())) {
            return true;
        }

        return auth()->user()->can($this->application->applicationType?->getVerifyPermissionAttribute()) && $this->application->canBeAccessedBy(auth()->user());
    }
}
