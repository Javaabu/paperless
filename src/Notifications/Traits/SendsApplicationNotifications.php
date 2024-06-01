<?php

namespace Javaabu\Paperless\Notifications\Traits;

use Illuminate\Support\Arr;
use Javaabu\Paperless\Support\StatusEvents\Models\StatusEvent;
;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationTypeBlueprint;
use Javaabu\Paperless\Notifications\Applicants\ApplicationStatusUpdatedNotification;

trait SendsApplicationNotifications
{
    public function getNotificationRecipientTypes(): array
    {
        return [
            'applicant',
            // 'group_leaders'
        ];
    }

    public function getNotifications(Application $application, $recipient): array
    {
        return [
            'applicant' => [ApplicationStatusUpdatedNotification::class],
            // 'group_leaders' => NotificationForGroupLeaders::class,
        ][$recipient];
    }

    public function getNotificationRecipients(Application $application, $recipient): array
    {
        return [
            'applicant' => [$application->applicant],
            // 'group_leaders' => $application->applicant->group->leaders,
        ][$recipient];
    }

    public function getNotificationParameters(Application $application, StatusEvent $statusEvent, string $notification_class): array
    {
        $parameter_mapping = [
            ApplicationStatusUpdatedNotification::class
            =>
                function (Application $application, StatusEvent $statusEvent, ApplicationTypeBlueprint $applicationType) {
                    return [
                        $application, $statusEvent, $applicationType
                    ];
                },
            // Add more closures here
        ];

        $parameter_closure = $parameter_mapping[$notification_class];

        if ( ! $parameter_closure instanceof \Closure) {
            return Arr::wrap($parameter_closure);
        }

        $parameters = app()->call($parameter_closure, [
            'application'     => $application,
            'statusEvent'     => $statusEvent,
            'applicationType' => $this,
        ]);

        return Arr::wrap($parameters);
    }
}
