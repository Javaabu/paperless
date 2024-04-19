<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

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

    public function getActionLabel(): string
    {
        return __('Mark As Approved');
    }

    public function getRemarks(): string
    {
        return __('Your application has been approved.');
    }

}
