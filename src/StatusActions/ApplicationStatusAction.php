<?php

namespace Javaabu\Paperless\StatusActions;

use Illuminate\Support\Carbon;
use Javaabu\Auth\User;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Helpers\Exceptions\InvalidOperationException;

abstract class ApplicationStatusAction
{
    public ?User $user = null;

    public function __construct(
        public Application $application,
        ?User $user = null,
    ) {
        $this->user = $user ?? auth()->user();
    }

    public function canSubmit(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('submit', $this->application)) {
            abort(403, 'Cannot submit the application request');
        }

        return true;
    }

    public function canMarkAsCancelled(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('markAsCancelled', $this->application)) {
            abort(403, 'Cannot cancel the application request');
        }

        return true;
    }

    public function canMarkAsRejected(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('markAsRejected', $this->application)) {
            abort(403, 'Cannot mark the application request as rejected.');
        }

        return true;
    }

    // MarkAsApproved
    public function canMarkAsApproved(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('markAsApproved', $this->application)) {
            abort(403, 'Cannot extend the application request deadline.');
        }

        return true;
    }

    public function submit(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot submit the application request.');
    }

    // markAsCancelled, markAsRejected, markAsIncomplete, resubmit, markAsVerified, undoVerification, extendDeadline, markAsProcessed, markAsPaid, markAsExpired, reRaisePayments, markAsApproved
    public function markAsCancelled(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot cancel the application request.');
    }

    public function markAsRejected(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot mark the application request as rejected.');
    }

    public function markAsApproved(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot mark the application request as approved.');
    }

}
