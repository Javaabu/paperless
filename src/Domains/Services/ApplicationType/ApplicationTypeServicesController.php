<?php

namespace Javaabu\Paperless\Domains\Services\ApplicationType;

use Illuminate\Http\Request;
use Javaabu\Helpers\Traits\HasOrderbys;
use Javaabu\Helpers\Http\Controllers\Controller;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class ApplicationTypeServicesController extends Controller
{
    use HasOrderbys;

    protected static function initOrderbys()
    {
        static::$orderbys = [
            'name' => __('Name'),
            'created_at' => __('Created At'),
            'id' => __('ID'),
        ];
    }

    public function index(ApplicationType $application_type, Request $request)
    {
        $title = __('All Application Types');
        $orderby = $this->getOrderBy($request, 'created_at');
        $order = $this->getOrder($request, 'created_at', $orderby);
        $per_page = $this->getPerPage($request);

        $services = $application_type->services()->orderBy($orderby, $order);

        if ($search = $request->input('search')) {
            $services->search($search);
            $title = __('Services matching \':search\'', ['search' => $search]);
        }

        $services = $services->paginate($per_page)
                             ->appends($request->except('page'));

        return view('paperless::admin.application-types.services.index', compact('application_type', 'services', 'title', 'per_page'));
    }

    public function show(ApplicationType $application_type)
    {
        return to_route('admin.application-types.edit', $application_type);
    }

    public function store(ApplicationType $application_type, ApplicationTypeServiceRequest $request)
    {
        $this->authorize('update', $application_type);
        $service = $request->input('service');
        $is_applied_automatically = $request->input('is_applied_automatically', false);

        $application_type->services()->attach($service, ['is_applied_automatically' => $is_applied_automatically]);

        $this->flashSuccessMessage();

        return to_route('admin.application-types.services.index', $application_type);
    }

    public function destroy(ApplicationType $application_type, Service $service, Request $request)
    {
        $this->authorize('update', $application_type);
        $application_type->services()->detach($service);

        if ($request->expectsJson()) {
            return response()->json(true, 204);
        }

        $this->flashSuccessMessage();

        return to_route('admin.application-types.services.index', $application_type);
    }

    public function bulk(ApplicationType $application_type, Request $request)
    {
        $this->authorize('view', $application_type);

        $this->validate($request, [
            'action' => 'required|in:delete,auto_applied,not_auto_applied',
            'services' => 'required|array',
            'services.*' => 'exists:application_type_service,id,application_type_id,' . $application_type->id,
        ]);

        $action = $request->input('action');
        $ids = $request->input('services', []);

        switch ($action) {
            case 'delete':
                $this->authorize('update', $application_type);
                $application_type->services()
                                 ->whereIn('application_type_service.id', $ids)
                                 ->each(function ($service) {
                                     $service->pivot->delete();
                                 });

                break;
            case 'auto_applied':
                $this->authorize('update', $application_type);
                $application_type->services()
                                 ->whereIn('application_type_service.id', $ids)
                                 ->each(function ($service) {
                                     $service->pivot->is_applied_automatically = true;
                                     $service->pivot->save();
                                 });

                break;

            case 'not_auto_applied':
                $this->authorize('update', $application_type);
                $application_type->services()
                                 ->whereIn('application_type_service.id', $ids)
                                 ->each(function ($service) {
                                     $service->pivot->is_applied_automatically = false;
                                     $service->pivot->save();
                                 });

                break;
        }

        $this->flashSuccessMessage();

        return to_route('admin.application-types.services.index', $application_type);
    }
}
