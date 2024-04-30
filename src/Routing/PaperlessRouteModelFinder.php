<?php

namespace Javaabu\Paperless\Routing;

class PaperlessRouteModelFinder
{
    public string $applicationModel;

    public function __construct()
    {
        $this->applicationModel = config('paperless.models.application');
    }

    public function forAdmin($model_id)
    {
        return $this->applicationModel::query()->findOrFail($model_id);
    }

    public function forPublic($model_id)
    {
        $query = $this->applicationModel::query();

        // Filter it out some more
        // Use cases include filtering out applications that are not accessible to the public
        // or filtering out applications that are not accessible to the user
        // useful for systems with delegations support

        return $query->findOrFail($model_id);
    }
}
