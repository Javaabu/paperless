<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Javaabu\Paperless\StatusActions\Transitions\CancelTransition;
use Javaabu\Paperless\StatusActions\Transitions\RejectTransition;
use Javaabu\Paperless\StatusActions\Transitions\SubmitTransition;
use Javaabu\Paperless\StatusActions\Transitions\VerifyTransition;
use Javaabu\Paperless\StatusActions\Transitions\ApproveTransition;
use Javaabu\Paperless\StatusActions\Transitions\ResubmitTransition;
use Javaabu\Paperless\StatusActions\Transitions\IncompleteTransition;
use Javaabu\Paperless\StatusActions\Transitions\UndoRejectionTransition;
use Javaabu\Paperless\StatusActions\Transitions\UndoVerificationTransition;

abstract class ApplicationStatus extends State
{
    abstract public function getColor(): string;
    abstract public function getLabel(): string;
    abstract public function getActionLabel(): string;

    public function canUpdateDocuments(): bool
    {
        return false;
    }

    public function getRemarks(): string
    {
        return '';
    }

    /**
     * @throws InvalidConfig
     */
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->allowTransition(Draft::class, PendingVerification::class, SubmitTransition::class)
            ->allowTransition(Draft::class, Cancelled::class, CancelTransition::class)
            ->allowTransition(PendingVerification::class, Verified::class, VerifyTransition::class)
            ->allowTransition(PendingVerification::class, Incomplete::class, IncompleteTransition::class)
            ->allowTransition(PendingVerification::class, Rejected::class, RejectTransition::class)
            ->allowTransition(PendingVerification::class, Cancelled::class, CancelTransition::class)
            ->allowTransition(Verified::class, PendingVerification::class, UndoVerificationTransition::class)
            ->allowTransition(Incomplete::class, PendingVerification::class, ResubmitTransition::class)
            ->allowTransition(Rejected::class, PendingVerification::class, UndoRejectionTransition::class)
            ->allowTransition(Incomplete::class, Cancelled::class, CancelTransition::class)
            ->allowTransition(Verified::class, Cancelled::class, CancelTransition::class)
            ->allowTransition(Verified::class, Approved::class, ApproveTransition::class)
        ;
    }
}
