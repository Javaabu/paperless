<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

use Spatie\ModelStates\State;

class Approved extends ApplicationStatus
{
    public static string $name = 'approved';

    public function getColor(): string
    {
        return 'success';
    }

    public function getLabel(): string
    {
        return __('Approved');
    }

}
