<?php

namespace Javaabu\Paperless\Domains\Applications;

use Javaabu\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;
use Javaabu\Paperless\Domains\Applications\Enums\ApplicationStatuses;

class ApplicationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->canAny(ApplicationType::getViewPermissionList());
    }

    public function view(User $user, Application $application): bool
    {
        $application->loadMissing('applicationType');
        if ($user->can($application->applicationType?->getViewAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getViewPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    public function create(User $user): bool
    {
        return $user->canAny(ApplicationType::getEditPermissionList());
    }

    public function update(User $user, Application $application): bool
    {
        if ($application->status != ApplicationStatuses::Draft) {
            return false;
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getEditAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getEditPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    public function delete(User $user, Application $application): bool
    {
        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getDeleteAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getDeletePermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    public function forceDelete(User $user, Application  $application): bool
    {
        return $user->can($application->applicationType?->getForceDeletePermissionAttribute());
    }

    public function restore(User $user, Application  $application): bool
    {
        return $this->trash($user);
    }

    public function trash(User $user): bool
    {
        return $user->canAny(ApplicationType::getDeletePermissionList())
            || $user->canAny(ApplicationType::getForceDeletePermissionList());
    }

    public function viewLogs(User $user, Application  $application): bool
    {
        return $user->can('viewAny', Activity::class) &&
               $user->can('update', $application);
    }

    public function editDocuments(User $user, Application $application): bool
    {
        return $user->can('update', $application);
    }

    // submit
    public function submit(User $user, Application $application): bool
    {
        if ($application->status != ApplicationStatuses::Draft) {
            return false;
        }

        /* @var \App\Models\User $user */
        return $user->can('view', $application);
    }

    // markAsCancelled
    public function markAsCancelled(User $user, Application $application): bool
    {
        if (! in_array($application->status, [
            ApplicationStatuses::Draft,
            ApplicationStatuses::Incomplete,
            ApplicationStatuses::PendingVerification,
            ApplicationStatuses::PendingPayment,
        ])) {
            return false;
        }

        if ($user instanceof PublicUser) {
            return $application->canBeAccessedByPublicUser($user);
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getCancelAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getCancelPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    // markAsRejected
    public function markAsRejected(User $user, Application $application): bool
    {
        if ($application->status != ApplicationStatuses::PendingVerification) {
            return false;
        }

        if ($user instanceof PublicUser) {
            return false;
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getVerifyAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getVerifyPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    // markAsIncomplete
    public function markAsIncomplete(User $user, Application $application): bool
    {
        return $user->can('markAsRejected', $application);
    }

    // resubmit
    public function resubmit(User $user, Application $application): bool
    {
        if ($application->status != ApplicationStatuses::Incomplete) {
            return false;
        }

        return $user->can('update', $application);
    }

    // markAsVerified
    public function markAsVerified(User $user, Application $application): bool
    {
        if ($application->status != ApplicationStatuses::PendingVerification) {
            return false;
        }

        if ($user instanceof PublicUser) {
            return false;
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getVerifyAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getVerifyPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    // undoVerification
    public function undoVerification(User $user, Application $application): bool
    {
        if (! in_array($application->status, [
            ApplicationStatuses::PendingApproval,
            ApplicationStatuses::Rejected,
        ])) {
            return false;
        }

        if ($user instanceof PublicUser) {
            return false;
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getVerifyAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getVerifyPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    // extendDeadline
    public function extendEta(User $user, Application $application): bool
    {
        if (! in_array($application->status, [
            ApplicationStatuses::PendingVerification,
            ApplicationStatuses::Processing,
        ])) {
            return false;
        }

        if ($user instanceof PublicUser) {
            return false;
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getExtendEtaAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getExtendEtaPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    // markAsPaid
    public function markAsPaid(User $user, Application $application): bool
    {
        if ($application->status != ApplicationStatuses::PendingPayment) {
            return false;
        }

        if ($user instanceof PublicUser) {
            return false;
            //            return $application->canBeAccessedByPublicUser($user);
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getPayAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getPayPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    // markAsApproved
    public function markAsApproved(User $user, Application $application): bool
    {
        if ($application->status != ApplicationStatuses::PendingApproval) {
            return false;
        }

        if ($user instanceof PublicUser) {
            return false;
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getApproveAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getApprovePermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    public function assignUser(User $user, Application $application): bool
    {
        if (in_array($application->status, [
            ApplicationStatuses::Rejected,
            ApplicationStatuses::Complete,
        ])) {
            return false;
        }

        if ($user instanceof PublicUser) {
            return false;
        }

        /* @var \App\Models\User $user */
        if ($user->can($application->applicationType?->getAssignUserAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getAssignUserPermissionAttribute()) && $application->canBeAccessedBy($user);
    }


}
