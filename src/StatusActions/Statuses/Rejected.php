<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

use Spatie\ModelStates\State;

class Rejected extends ApplicationStatus
{
    public static string $name = 'rejected';

    public function getColor(): string
    {
        return 'danger';
    }

    public function getLabel(): string
    {
        return __('Rejected');
    }

    public function getActionLabel(): string
    {
        return __('Mark As Rejected');
    }
}
