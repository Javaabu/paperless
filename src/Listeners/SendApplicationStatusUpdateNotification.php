<?php

namespace Javaabu\Paperless\Listeners;

use Notification;
use Javaabu\Paperless\Events\UpdatedApplicationStatus;

class SendApplicationStatusUpdateNotification
{
    public function handle(UpdatedApplicationStatus $event): void
    {
        $application = $event->application;
        $status_event = $event->statusEvent;

        $applicant = $application->applicant;

        $application_type = $application->applicationType->getApplicationTypeClassInstance();

        // The notifications for this recipient type
        $notifications = $application_type->getNotifications($application, $applicant->getMorphClass());

        // Foreach notification, get the recipients and send the notification
        foreach ($notifications as $notification_class) {
            $recipients = $application_type->getNotificationRecipients($application, $applicant->getMorphClass());
            $notification_parameters = $application_type->getNotificationParameters(
                $application,
                $status_event,
                $notification_class
            );

            Notification::send($recipients, new $notification_class(...$notification_parameters));
        }
    }
}
