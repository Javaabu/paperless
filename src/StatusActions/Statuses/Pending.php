<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

use Spatie\ModelStates\State;

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

}
