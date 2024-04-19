<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

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

    public function getRemarks(): string
    {
        return __('Your application is incomplete.');
    }
}
