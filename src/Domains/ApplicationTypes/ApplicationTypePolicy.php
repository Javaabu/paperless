<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Javaabu\Auth\UserContract;
use Javaabu\Activitylog\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(UserContract $user): bool
    {
        return $user->can('view_application_types');
    }

    public function view(UserContract $user, ApplicationType $application_type): bool
    {
        return $this->update($user, $application_type);
    }

    public function create(UserContract $user): bool
    {
        return false;
    }

    public function update(UserContract $user, ApplicationType $application_type): bool
    {
        return $user->can('edit_application_types');
    }

    public function delete(UserContract $user, ApplicationType $application_type): bool
    {
        return false;
    }

    public function viewLogs(UserContract $user, ApplicationType  $application_type): bool
    {
        return $user->can('viewAny', Activity::class) &&
               $this->update($user, $application_type);
    }

    public function viewStats(UserContract $user, ApplicationType $application_type): bool
    {
        return $user->can('view_stats') && $user->can('view', $application_type);
    }
}
