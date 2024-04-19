<?php

namespace Javaabu\Paperless\StatusActions\Transitions;

use Spatie\ModelStates\Transition;
use Javaabu\Paperless\StatusActions\Statuses\Draft;
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredFields;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredDocuments;

class SubmitTransition extends Transition
{
    protected CheckPresenceOfRequiredDocuments $check_presence_of_required_documents;
    protected CheckPresenceOfRequiredFields $check_presence_of_required_fields;

    public function __construct(
        public Application $application,
    ) {
        $this->check_presence_of_required_documents = app(CheckPresenceOfRequiredDocuments::class);
        $this->check_presence_of_required_fields = app(CheckPresenceOfRequiredFields::class);
    }

    public function handle(): Application
    {
        if (! $this->check_presence_of_required_fields->handle($this->application)) {
            throw new InvalidOperationException('Cannot submit the application request. Please fill all the required fields.');
        }

        if (! $this->check_presence_of_required_documents->handle($this->application)) {
            throw new InvalidOperationException('Cannot submit the application request. Please upload all the required documents.');
        }

        $this->application->doBeforeSubmitting();

        $application_eta_days = $this->application?->applicationType?->eta_duration ?? 0;
        $this->application->status = new PendingVerification($this->application);
        $this->application->submitted_at = now();
        $this->application->eta_at = now()->addDays($application_eta_days);
        $this->application->save();

        $this->application->createStatusEvent(
            new PendingVerification($this->application),
            $remarks ?? (new PendingVerification($this->application))->getRemarks()
        );

        $this->application->doAfterSubmitting();

        return $this->application;
    }

    public function canTransition(): bool
    {
        if ($this->application->status->getValue() != Draft::getMorphClass()) {
            return false;
        }

        return auth()->user()->can('view', $this->application);
    }
}
