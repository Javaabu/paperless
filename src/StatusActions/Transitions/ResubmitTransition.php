<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Exception;
use Spatie\ModelStates\Transition;
use Javaabu\Paperless\Events\UpdatedApplicationStatus;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Events\UpdatingApplicationStatus;
use Javaabu\Paperless\StatusActions\Statuses\Incomplete;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredFields;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredDocuments;

class ResubmitTransition extends Transition
{
    protected CheckPresenceOfRequiredDocuments $check_presence_of_required_documents;
    protected CheckPresenceOfRequiredFields $check_presence_of_required_fields;

    public function __construct(
        public Application   $application,
        public string | null $remarks = null,
    ) {
        $this->check_presence_of_required_documents = app(CheckPresenceOfRequiredDocuments::class);
        $this->check_presence_of_required_fields = app(CheckPresenceOfRequiredFields::class);
    }

    /**
     * @throws Exception
     */
    public function handle(): Application
    {
        if (! $this->check_presence_of_required_fields->handle($this->application)) {
            throw new InvalidOperationException('Cannot submit the application request. Please fill all the required fields.');
        }

        if (! $this->check_presence_of_required_documents->handle($this->application)) {
            throw new InvalidOperationException('Cannot submit the application request. Please upload all the required documents.');
        }

        $this->application->callServiceFunction('doBeforeResubmitting');

        UpdatingApplicationStatus::dispatch($this->application);

        $application_eta_days = $this->application?->applicationType?->eta_duration ?? 0;
        $this->application->status = new PendingVerification($this->application);
        $this->application->verifiedBy()->dissociate();
        $this->application->verified_at = null;
        $this->application->eta_at = now()->addDays($application_eta_days);
        $this->application->save();

        $status_event = $this->application->createStatusEvent(
            new PendingVerification($this->application),
            $this->remarks ?? (new PendingVerification($this->application))->getRemarks()
        );

        $this->application->callServiceFunction('doAfterResubmitting');

        // Give a fresh instance of the application as at this point, things would have changed.
        UpdatedApplicationStatus::dispatch($this->application->fresh(), $status_event);

        return $this->application;
    }

    public function canTransition(): bool
    {
        if ($this->application->status->getValue() != Incomplete::getMorphClass()) {
            return false;
        }

        return auth()->user()->can('update', $this->application);
    }
}
