<?php

namespace Javaabu\Paperless\StatusActions\Statuses;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use Spatie\ModelStates\Exceptions\InvalidConfig;
use Javaabu\Paperless\StatusActions\Transitions\SubmitTransition;
use Javaabu\Paperless\StatusActions\Transitions\CancelTransition;
use Javaabu\Paperless\StatusActions\Transitions\RejectTransition;
use Javaabu\Paperless\StatusActions\Transitions\ApproveTransition;

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
            ->allowTransition(Draft::class, Pending::class, SubmitTransition::class)
            ->allowTransition(Draft::class, Cancelled::class, CancelTransition::class)
            ->allowTransition(Pending::class, Approved::class, ApproveTransition::class)
            ->allowTransition(Pending::class, Rejected::class, RejectTransition::class)
            ->allowTransition(Pending::class, Cancelled::class, CancelTransition::class);
    }
}
