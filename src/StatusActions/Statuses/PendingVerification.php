<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

class PendingVerification extends ApplicationStatus
{
    public static string $name = 'pending_verification';

    public function getColor(): string
    {
        return 'secondary';
    }

    public function getLabel(): string
    {
        return __('Pending Verification');
    }

    public function getActionLabel(): string
    {
        $application = $this->getModel();


        if ($application->status->getValue() == Incomplete::getMorphClass()) {
            return __('Resubmit');
        }

        if ($application->status->getValue() == Verified::getMorphClass()) {
            return __('Undo Verification');
        }

        if ($application->status->getValue() == Rejected::getMorphClass()) {
            return __('Undo Rejection');
        }

        return __('Submit');
    }
}
