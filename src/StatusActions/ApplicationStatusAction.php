<?php

namespace Javaabu\Paperless\StatusActions;

use Illuminate\Support\Carbon;
use Javaabu\Auth\User;
use Javaabu\Paperless\Domains\Applications\Application;

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

    public function canMarkAsIncomplete(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('markAsIncomplete', $this->application)) {
            abort(403, 'Cannot mark the application request as incomplete.');
        }

        return true;
    }

    public function canResubmit(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('resubmit', $this->application)) {
            abort(403, 'Cannot resubmit the application request');
        }

        return true;
    }

    public function canMarkAsVerified(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('markAsVerified', $this->application)) {
            abort(403, 'Cannot mark the application request as verified.');
        }

        return true;
    }

    public function canUndoVerification(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('undoVerification', $this->application)) {
            abort(403, 'Cannot undo the application request verification.');
        }

        return true;
    }

    public function canExtendEta(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('extendEta', $this->application)) {
            abort(403, 'Cannot extend the application request eta.');
        }

        return true;
    }

    public function canMarkAsProcessed(): bool
    {
        return $this->application->applicationType?->canMarkAsProcessed($this->application);
    }

    // MarkAsPaid
    public function canMarkAsPaid(): bool
    {
        if (app()->runningInConsole()) { // system command
            return true;
        }

        if (! auth()->user() || ! auth()->user()->can('markAsPaid', $this->application)) {
            abort(403, 'Cannot extend the application request deadline.');
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

    public function canAssignUser(): bool
    {
        if (! auth()->user() || ! auth()->user()->can('assignUser', $this->application)) {
            abort(403, 'Cannot assign user to the application request.');
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

    public function markAsIncomplete(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot mark the application request as incomplete.');
    }

    public function resubmit(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot resubmit the application request.');
    }

    public function markAsVerified(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot mark the application request as verified.');
    }

    public function undoVerification(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot undo the application request verification.');
    }

    public function extendEta(Carbon $new_deadline, ?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot extend the application request deadline.');
    }

    public function markAsProcessed(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot mark the application request as processed.');
    }

    public function markAsPaid(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot mark the application request as paid.');
    }

    public function markAsApproved(?string $remarks = null): Application
    {
        throw new InvalidOperationException('Cannot mark the application request as approved.');
    }

    public function assignUser(User $user): Application
    {
        throw new InvalidOperationException('Cannot assign user to the application request.');
    }

}
