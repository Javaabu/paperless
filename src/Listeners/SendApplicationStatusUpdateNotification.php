<?php

namespace Javaabu\Paperless\Listeners;

use Javaabu\Paperless\Events\UpdatedApplicationStatus;

class SendApplicationStatusUpdateNotification
{
    public function handle(UpdatedApplicationStatus $event): void
    {
        $application = $event->application;

        $application_type = $application->applicationType;

        dd($application_type->get);

    }
}
