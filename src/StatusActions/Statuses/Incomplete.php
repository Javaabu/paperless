<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

use Spatie\ModelStates\State;

class Incomplete extends ApplicationStatus
{
    public static string $name = 'incomplete';

    public function getColor(): string
    {
        return 'warning';
    }

    public function getLabel(): string
    {
        return __('Incomplete');
    }

    public function canUpdateDocuments(): bool
    {
        return true;
    }

    public function getActionLabel(): string
    {
        return __('Mark As Incomplete');
    }
}
