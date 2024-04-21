<?php

namespace Javaabu\Paperless\StatusActions\Actions;

use Javaabu\Paperless\Domains\Applications\Application;

class CheckPresenceOfRequiredDocuments
{
    public function handle(Application $application): bool
    {
        $required_documents = $application->applicationType->documentTypes()->wherePivot('is_required', true)->pluck('document_types.id');
        $uploaded_documents = $application->getMedia('documents')->whereNotNull('document_type_id')->pluck('document_type_id');

        return $required_documents->diff($uploaded_documents)->isEmpty();
    }
}
