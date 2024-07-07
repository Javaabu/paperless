<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Draft;
use Javaabu\Paperless\Events\UpdatedApplicationStatus;
use Javaabu\Paperless\StatusActions\Statuses\Verified;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Events\UpdatingApplicationStatus;
use Javaabu\Paperless\StatusActions\Statuses\Cancelled;
use Javaabu\Paperless\StatusActions\Statuses\Incomplete;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;

class CancelTransition extends Transition
{
    public function __construct(
        public Application $application,
        public string | null $remarks = null,
    ) {
    }

    public function handle(): Application
    {
        $this->application->callServiceFunction('doBeforeMarkingAsCancelled');

        UpdatingApplicationStatus::dispatch($this->application);

        $this->application->status = new Cancelled($this->application);
        $this->application->save();

        $status_event = $this->application->createStatusEvent(
            new Cancelled($this->application),
            $this->remarks ?? (new Cancelled($this->application))->getRemarks()
        );

        $this->application->callServiceFunction('doAfterMarkingAsCancelled');

        // Give a fresh instance of the application as at this point, things would have changed.
        UpdatedApplicationStatus::dispatch($this->application->fresh(), $status_event);

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

        dd($this->application->canBeAccessedBy(auth()->user()));
        dd(auth()->user()->can('cancel_register_new_group_application_types'));
        dd($this->application->applicationType?->getCancelPermissionAttribute());

        return auth()->user()->can(
            $this->application->applicationType?->getCancelPermissionAttribute()
        )
            && $this->application->canBeAccessedBy(
                auth()->user()
            );
    }
}
