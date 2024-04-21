<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Javaabu\Helpers\Traits\HasOrderbys;
use Javaabu\Helpers\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class ApplicationTypesController extends Controller
{
    use HasOrderbys;

    public function getModelClass(): string
    {
        return config('paperless.models.application_type');
    }

    public function __construct()
    {
        //        $this->authorizeResource($this->getModelClass());
    }

    protected static function initOrderbys(): void
    {
        static::$orderbys = [
            'name'       => __('Name'),
            'created_at' => __('Created At'),
            'id'         => __('ID'),
        ];
    }

    public function index(Request $request): View
    {
        $title    = __('All Application Types');
        $orderby  = $this->getOrderBy($request, 'created_at');
        $order    = $this->getOrder($request, 'created_at', $orderby);
        $per_page = $this->getPerPage($request);

        $application_types = $this->getModelClass()::query()
                                                   ->orderBy($orderby, $order);

        if ($search = $request->input('search')) {
            $application_types->search($search);
            $title = __('Application Types matching \':search\'', ['search' => $search]);
        }

        $application_types = $application_types->with(['documentTypes', 'services'])->paginate($per_page)
                                           ->appends($request->except('page'));

        return view('paperless::admin.application-types.index', compact('application_types', 'title', 'per_page'));
    }

    public function show(ApplicationType $application_type): RedirectResponse
    {
        return to_route('admin.application-types.edit', $application_type);
    }

    public function edit(ApplicationType $application_type): View
    {
        return view('paperless::admin.application-types.edit', compact('application_type'));
    }

    public function update(ApplicationTypesRequest $request, ApplicationType $application_type): RedirectResponse
    {
        $application_type->fill($request->validated());
        $application_type->save();

        $this->flashSuccessMessage();

        return to_route('admin.application-types.edit', $application_type);
    }

    public function bulk(Request $request)
    {
        $this->authorize('viewAny', ApplicationType::class);

        $this->validate($request, [
            'action'              => 'required|in:',
            'application_types'   => 'required|array',
            'application_types.*' => 'exists:application_types,id',
        ]);

        $action = $request->input('action');
        $ids    = $request->input('application_types', []);

        //        switch ($action) {
        //        }

        $this->flashSuccessMessage();

        return to_route('admin.application-types.index');
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function upload(ApplicationType $application_type, Request $request)
    {
        $this->authorize('update', $application_type);

        $type = $request->hasFile('image') ? 'file' : 'url';

        $rules = [
            'url'   => ['string', 'url', 'required_without:image'],
            'image' => AllowedMimeTypes::getValidationRule('image') . '|required_without:url',
        ];

        $this->validate($request, $rules);

        $media = null;

        if ($type == 'url') {
            $media = $application_type->addMediaFromUrl($request->input('url'));
        } else {
            $media = $application_type->addMediaFromRequest('image');
        }

        $media = $media->toMediaCollection('description_images');

        return response()->json([
            'success' => 1,
            'file'    => [
                'url'      => $media->getUrl('large'),
                'original' => $media->getUrl(),
                'id'       => $media->id,
            ],
        ]);
    }

    public function stats(ApplicationType $application_type)
    {
        $this->authorize('viewStats', $application_type);

        $filters = $this->statsFilters($application_type);

        return view('admin.application-types.stats', compact(
            'application_type',
            'filters',
        ));
    }

    public function statsExport(ApplicationType $application_type, StatsRequest $request)
    {
        $this->authorize('viewStats', $application_type);

        return $this->exportStats($request, $this->statsFilters($application_type), $application_type->name.' User');
    }

    public function statsFilters(ApplicationType $application_type)
    {
        return [
            'application_type' => $application_type->id,
        ];
    }


    public function exportStats(StatsRequest $request, $filters = [], $export_title = '')
    {
        $this->validateStatsFilters($request);

        $metric = $request->input('metric');
        $mode   = $request->input('mode');

        if ($request->filled('date_range')) {
            $range = $request->input('date_range');
        } else {
            $range = [
                $request->input('date_from'),
                $request->input('date_to'),
            ];
        }

        $compare_range = null;

        if ($request->filled('compare_date_range')) {
            $compare_range = $request->input('compare_date_range');
        } elseif ($request->filled('compare_date_from')) {
            $compare_range = [
                $request->input('compare_date_from'),
                $request->input('compare_date_to'),
            ];
        } elseif ($request->input('compare')) {
            $compare_range = StatsRepository::getPreviousDateRange($range);
        }

        $stats = StatsRepository::createFromMetric($metric, $range, $filters);

        if ($compare_range) {
            $compare = StatsRepository::createFromMetric($metric, $compare_range, $filters);
        } else {
            $compare = null;
        }

        $formatter = new CombinedStatsFormatter($stats, $compare);
        $exporter  = new StatsExport($formatter, $mode);

        $title = $exporter->getReportTitle();

        $document_title = slug_to_title($export_title ?: get_setting('app_name'), '-').
            ' '.$title.' '.
            $stats->formattedDateRange('Ymd', '-').
            ($compare ? ' '.$compare->formattedDateRange('Ymd', '-') : '');

        return $exporter->download($document_title.'.csv');
    }
}
