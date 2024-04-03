<?php

namespace Javaabu\Paperless\Domains\DocumentTypes;


use Javaabu\Auth\User;
use Javaabu\Activitylog\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('view_document_types');
    }

    public function view(User $user, DocumentType $document_type): bool
    {
        return $this->update($user, $document_type);
    }

    public function create(User $user): bool
    {
        return $user->can('edit_document_types');
    }

    public function update(User $user, DocumentType $document_type): bool
    {
        return $user->can('edit_document_types');
    }

    public function delete(User $user, DocumentType $document_type): bool
    {
        return $user->can('delete_document_types');
    }

    public function forceDelete(User $user, DocumentType $document_type): bool
    {
        return $user->can('force_delete_document_types');
    }

    public function restore(User $user, DocumentType $document_type): bool
    {
        return $this->trash($user);
    }

    public function trash(User $user): bool
    {
        return $user->can('delete_document_types') || $user->can('force_delete_document_types');
    }

    public function viewLogs(User $user, DocumentType $document_type): bool
    {
        return $user->can('viewAny', Activity::class) &&
            $this->update($user, $document_type);
    }
}
