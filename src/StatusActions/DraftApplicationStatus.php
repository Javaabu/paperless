<?php

namespace Javaabu\Paperless\StatusActions;

use Javaabu\Auth\User;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;
use Javaabu\Paperless\StatusActions\Traits\HasCancelAction;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredFields;
use Javaabu\Paperless\StatusActions\Actions\CheckPresenceOfRequiredDocuments;

class DraftApplicationStatus extends ApplicationStatusAction
{
    use HasCancelAction;

    protected CheckPresenceOfRequiredDocuments $check_presence_of_required_documents;
    protected CheckPresenceOfRequiredFields $check_presence_of_required_fields;

    public function __construct(
        public Application $application,
        ?User              $user = null,
    ) {
        parent::__construct($application, $user);
        $this->check_presence_of_required_documents = app(CheckPresenceOfRequiredDocuments::class);
        $this->check_presence_of_required_fields = app(CheckPresenceOfRequiredFields::class);
    }

    /**
     * @throws \Exception
     */
    public function submit(?string $remarks = null): Application
    {
        $this->canSubmit();

        if (! $this->check_presence_of_required_fields->handle($this->application)) {
            throw new InvalidOperationException('Cannot submit the application request. Please fill all the required fields.');
        }

        if (! $this->check_presence_of_required_documents->handle($this->application)) {
            throw new InvalidOperationException('Cannot submit the application request. Please upload all the required documents.');
        }

        $this->application->doBeforeSubmitting();

        $application_eta_days = $this->application?->applicationType?->eta_duration ?? 0;
        $this->application->status = config('paperless.enums.application_status')::Pending->value;
        $this->application->submitted_at = now();
        $this->application->eta_at = now()->addDays($application_eta_days);
        $this->application->save();

        $this->application->createStatusEvent(
            config('paperless.enums.application_status')::Pending->value,
            $remarks ?? config('paperless.enums.application_status')::Pending->getRemarks()
        );

        $this->application->doAfterSubmitting();

        return $this->application;
    }
}
