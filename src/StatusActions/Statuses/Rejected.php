<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

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

    public function getRemarks(): string
    {
        return __('Your application has been rejected.');
    }
}
