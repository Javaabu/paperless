<?php

namespace Javaabu\Paperless\Domains\Applications\Enums;

use Javaabu\Auth\User;
use Javaabu\Paperless\Enums\IsEnum;
use Javaabu\Paperless\Enums\NativeEnumsTrait;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\StatusActions\DraftApplicationStatus;
use Javaabu\Paperless\StatusActions\PendingApplicationStatus;

enum ApplicationStatuses: string implements IsEnum
{
    use NativeEnumsTrait;

    case Draft = 'draft';
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';

    public static function labels(): array
    {
        return [
            self::Draft->value => __("Draft"),
            self::Pending->value => __("Pending"),
            self::Approved->value => __("Approved"),
            self::Rejected->value => __("Rejected"),
            self::Cancelled->value => __("Cancelled"),
        ];
    }

    public static function colors(): array
    {
        return [
            self::Draft->value => "light",
            self::Pending->value => "secondary",
            self::Approved->value => "success",
            self::Rejected->value => "danger",
            self::Cancelled->value => "dark",
        ];
    }

    public static function statusActions(): array
    {
        return [
            self::Draft->value => DraftApplicationStatus::class,
            self::Pending->value => PendingApplicationStatus::class,
            self::Approved->value => CompleteApplicationStatus::class,
            self::Rejected->value => RejectedApplicationStatus::class,
            self::Cancelled->value => CancelledApplicationStatus::class,
        ];
    }

    public static function statusRemarks(): array
    {
        return [
            self::Draft->value => __("Your application is currently in draft."),
            self::Pending->value => __("Your application is pending verification."),
            self::Rejected->value => __("Your application has been rejected."),
            self::Approved->value => __("Your application is complete."),
            self::Cancelled->value => __("Your application has been cancelled."),
        ];
    }

    public static function actions(): array
    {
        return [
            'submit' => 'secondary',
            'markAsCancelled' => 'dark',
            'markAsRejected' => 'danger',
            'markAsApproved' => 'success',
        ];
    }

    public static function manualActions(): array
    {
        return [
        ];
    }

    public static function getProcessActions(): array
    {
        $actions = self::actions();
        $manual_actions = self::manualActions();

        return array_diff_key($actions, array_flip($manual_actions));
    }

    public static function getAllowedActions(User $user, Application $application): array
    {
        $allowed_actions = [];
        foreach (self::actions() as $action => $color) {
            if ($user->can($action, $application)) {
                $allowed_actions[] = $action;
            }
        }

        return $allowed_actions;
    }

    public function isLocked(): bool
    {
        return in_array($this->value, [
            self::Approved->value,
            self::Cancelled->value,
            self::Rejected->value,
        ]);
    }

    public function getRemarks(): string
    {
        return self::statusRemarks()[$this->value] ?? '';
    }

    public function getStatusAction(): string
    {
        return self::statusActions()[$this->value];
    }

    public function getColor(): string
    {
        return self::colors()[$this->value];
    }
}
