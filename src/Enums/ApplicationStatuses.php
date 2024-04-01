<?php

namespace Javaabu\Paperless\Enums;

use Javaabu\Helpers\Enums\IsEnum;
use Javaabu\Helpers\Enums\NativeEnumsTrait;
use Javaabu\StatusEvents\Enums\IsStatusEventEnum;

enum ApplicationStatuses: string implements IsEnum, IsStatusEventEnum
{
    use NativeEnumsTrait;

    case DRAFT = 'draft';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';

    public static function statusActions(): array
    {
        // TODO: Implement statusActions() method.
    }

    public static function statusRemarks(): array
    {
        return [
            self::DRAFT->value => __('Your application is currently in draft.'),
            self::PENDING->value => __('Your application is currently pending.'),
            self::APPROVED->value => __('Your application has been approved.'),
            self::REJECTED->value => __('Your application has been rejected.'),
            self::CANCELLED->value => __('Your application has been cancelled.'),
        ];
    }
}
