<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Pending;
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
        return $this->application;
    }

    public function canTransition(): bool
    {
        if ($this->application->status->getValue() != Pending::getMorphClass()) {
            return false;
        }

        if (auth()->user()->can($this->application->applicationType?->getApproveAnyPermissionAttribute())) {
            return true;
        }

        return auth()->user()->can($this->application->applicationType?->getApprovePermissionAttribute()) && $this->application->canBeAccessedBy(auth()->user());
    }
}
