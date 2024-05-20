<?php

namespace Javaabu\Paperless\Support\Media;


use Illuminate\Auth\Access\HandlesAuthorization;

class MediaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the media.
     */
    public function view($user, Media $media): bool
    {
        $media_related_model = $media->model;

        $paperless_application_model = config('paperless.models.application');

        if ($media_related_model instanceof $paperless_application_model) {
            return $user->can('view', $media_related_model);
        }

        return false;
    }

    /**
     * Determine whether the user can create media.
     */
    public function create($user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the media.
     */
    public function update($user, Media $media): bool
    {
        return $media->model && $user->can('editDocuments', $media->model);

        return true;
    }

    /**
     * Determine whether the user can delete the media.
     */
    public function delete($user, Media $media): bool
    {
        // return $this->update($user, $media);

        return true;
    }
}
