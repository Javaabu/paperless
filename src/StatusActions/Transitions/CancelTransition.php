<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use App\Application\Enums\ApplicationStatuses;
use Javaabu\Paperless\StatusActions\Statuses\Draft;
use Javaabu\Paperless\StatusActions\Statuses\Approved;
use Javaabu\Paperless\StatusActions\Statuses\Verified;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\StatusActions\Statuses\Cancelled;
use Javaabu\Paperless\StatusActions\Statuses\Incomplete;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;

class CancelTransition extends Transition
{
    public function __construct(
        public Application $application,
    ) {
    }

    public function handle(): Application
    {
        $this->application->doBeforeMarkingAsCancelled();

        $this->application->status = new Cancelled($this->application);
        $this->application->save();

        $this->application->createStatusEvent(
            new Cancelled($this->application),
            $remarks ?? (new Cancelled($this->application))->getRemarks()
        );

        $this->application->doAfterMarkingAsCancelled();
        return $this->application;
    }


    public function canTransition(): bool
    {
        if (! in_array($this->application->status->getValue(), [
            Draft::getMorphClass(),
            PendingVerification::getMorphClass(),
            Incomplete::getMorphClass(),
            Verified::getMorphClass(),
        ])) {
            return false;
        }

        if (auth()->user()->can($this->application->applicationType?->getCancelAnyPermissionAttribute())) {
            return true;
        }

        return auth()->user()->can($this->application->applicationType?->getCancelPermissionAttribute()) && $this->application->canBeAccessedBy(auth()->user());
    }
}
