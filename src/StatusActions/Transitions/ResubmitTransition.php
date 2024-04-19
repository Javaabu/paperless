<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Draft;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;
use Javaabu\Paperless\StatusActions\Statuses\Approved;
use Javaabu\Paperless\StatusActions\Statuses\Rejected;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredFields;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredDocuments;

class ResubmitTransition extends Transition
{

    public function __construct(
        public Application $application,
    ) {
    }

    public function canTransition(): bool
    {
        if (! in_array($this->application->status->getValue(), [
            Draft::getMorphClass(),
            PendingVerification::getMorphClass(),
        ])) {
            return false;
        }

        if (auth()->user()->can($this->application->applicationType?->getCancelAnyPermissionAttribute())) {
            return true;
        }

        return auth()->user()->can($this->application->applicationType?->getCancelPermissionAttribute()) && $this->application->canBeAccessedBy(auth()->user());
    }
}
