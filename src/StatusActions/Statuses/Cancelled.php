<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

use Spatie\ModelStates\State;

class Cancelled extends ApplicationStatus
{
    public static string $name = 'cancelled';

    public function getColor(): string
    {
        return 'dark';
    }

    public function getLabel(): string
    {
        return __('Cancelled');
    }

    public function getActionLabel(): string
    {
        return __('Mark As Cancelled');
    }
}
