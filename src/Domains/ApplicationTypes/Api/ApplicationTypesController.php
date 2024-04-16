<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes\Api;

use Schema;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Javaabu\QueryBuilder\Http\Controllers\ApiController;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class ApplicationTypesController extends ApiController
{
    protected bool $allow_unlimited_results = true;

    protected function getBaseQuery(): Builder
    {
        return ApplicationType::query();
    }

    protected function getAllowedFields(): array
    {
        return Schema::getColumnListing('application_types');
    }

    protected function getAllowedIncludes(): array
    {
        return [
        ];
    }

    /**
     * Get the allowed appends
     *
     * @return array
     */
    protected function getAllowedAppends(): array
    {
        return [
            'formatted_name' => [
                'name',
                'code',
            ],
        ];
    }

    protected function getAllowedSorts(): array
    {
        return [
            'id',
            'name',
            'code',
            'created_at',
            'updated_at',
        ];
    }

    protected function getDefaultSort(): string
    {
        return 'id';
    }

    protected function getAllowedFilters(): array
    {
        return [
            'name',
            'code',
            AllowedFilter::scope('search'),
            AllowedFilter::scope('applicant_type', 'whereHasEntityType'),
        ];
    }
}
