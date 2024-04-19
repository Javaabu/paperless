<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

class Draft extends ApplicationStatus
{
    public static string $name = 'draft';

    public function getColor(): string
    {
        return 'light';
    }

    public function getLabel(): string
    {
        return __('Draft');
    }

    public function canUpdateDocuments(): bool
    {
        return true;
    }

    public function getActionLabel(): string
    {
        return __('Save As Draft');
    }
}
