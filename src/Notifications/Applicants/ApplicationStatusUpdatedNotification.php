<?php

namespace Javaabu\Paperless\Notifications\Applicants;

use Javaabu\Paperless\Notifications\BaseApplicationStatusUpdateNotification;

class ApplicationStatusUpdatedNotification extends BaseApplicationStatusUpdateNotification
{
    protected function getTitle($notifiable): string
    {
        return __("Application :application_no Status Updated", ['application_no' => $this->application->formatted_id]);
    }
}
