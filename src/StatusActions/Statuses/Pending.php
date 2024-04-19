<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

class Pending extends ApplicationStatus
{
    public static string $name = 'pending';

    public function getColor(): string
    {
        return 'secondary';
    }

    public function getLabel(): string
    {
        return __('Pending');
    }

    public function getActionLabel(): string
    {
        $application = $this->getModel();

        if ($application->status->getValue() == Draft::getMorphClass()) {
            return __('Submit');
        }

        return __('Mark As Pending');
    }
}
