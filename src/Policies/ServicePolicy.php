<?php

namespace Javaabu\Paperless\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Javaabu\Activitylog\Models\Activity;
use Javaabu\Auth\UserContract;
use Javaabu\Paperless\Models\Service;

class ServicePolicy
{
    use HandlesAuthorization;

    public function viewAny(UserContract $user): bool
    {
        return $user->can('view_services');
    }

    public function view(UserContract $user, Service $service): bool
    {
        return $this->update($user, $service);
    }

    public function create(UserContract $user): bool
    {
        return $user->can('edit_services');
    }

    public function update(UserContract $user, Service $service): bool
    {
        return $user->can('edit_services');
    }

    public function delete(UserContract $user, Service $service): bool
    {
        return $user->can('delete_services');
    }

    public function forceDelete(UserContract $user, Service  $service): bool
    {
        return $user->can('force_delete_services');
    }

    public function restore(UserContract $user, Service  $service): bool
    {
        return $this->trash($user);
    }

    public function trash(UserContract $user): bool
    {
        return $user->can('delete_services') || $user->can('force_delete_services');
    }

    public function viewLogs(UserContract $user, Service  $service): bool
    {
        return $user->can('viewAny', Activity::class) &&
               $this->update($user, $service);
    }
}
