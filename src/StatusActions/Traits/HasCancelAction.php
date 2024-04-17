<?php

namespace Javaabu\Paperless\StatusActions\Traits;

use Javaabu\Paperless\Domains\Applications\Application;

trait HasCancelAction
{
    public function markAsCancelled(?string $remarks = null): Application
    {
        $application_status_enum = config('paperless.enums.application_status');
        $this->canMarkAsCancelled();

        $this->application->doBeforeMarkingAsCancelled();

        if (config('paperless.relations.services')) {
            $this->application->payments()->pending()->get()->each->cancel();
        }

        $this->application->status = $application_status_enum::Cancelled->value;
        $this->application->save();


        $this->application->createStatusEvent(
            $application_status_enum::Cancelled->value,
            $remarks ?? $application_status_enum::Cancelled->getRemarks()
        );

        $this->application->doAfterMarkingAsCancelled();
        return $this->application;
    }
}
