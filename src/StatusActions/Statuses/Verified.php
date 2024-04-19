<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

use Spatie\ModelStates\State;

class Verified extends ApplicationStatus
{
    public static string $name = 'verified';

    public function getColor(): string
    {
        return 'primary';
    }

    public function getLabel(): string
    {
        return __('Verified');
    }

    public function canUpdateDocuments(): bool
    {
        return true;
    }

    public function getActionLabel(): string
    {
        return __('Mark As Verified');
    }
}
