<?php

namespace Javaabu\Paperless\Domains\Applications;

use Javaabu\Auth\User;
use Javaabu\Activitylog\Models\Activity;
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
        if ($user->can($application->applicationType?->getEditAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getEditPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    public function updateDocuments(User $user, Application $application): bool
    {
        if (! $application->status->canUpdateDocuments()) {
            return false;
        }

        if ($user->can($application->applicationType?->getEditAnyPermissionAttribute())) {
            return true;
        }

        return $user->can($application->applicationType?->getEditPermissionAttribute()) && $application->canBeAccessedBy($user);
    }

    public function delete(User $user, Application $application): bool
    {
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
}
