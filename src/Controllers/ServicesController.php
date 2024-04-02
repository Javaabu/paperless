<?php

namespace Javaabu\Paperless\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;

class ServicesController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(config('paperless.models.service'));
    }

    protected static function initOrderbys()
    {
        static::$orderbys = [
            'name' => __('Name'),
            'created_at' => __('Created At'),
            'id' => __('ID'),
        ];
    }

    public function index(Request $request, $trashed = false)
    {
        $title = __('All Services');
        $orderby = $this->getOrderBy($request, 'created_at');
        $order = $this->getOrder($request, 'created_at', $orderby);
        $per_page = $this->getPerPage($request);

        $services = Service::orderBy($orderby, $order);

        if ($search = $request->input('search')) {
            $services->search($search);
            $title = __('Services matching \':search\'', ['search' => $search]);
        }

        if ($trashed) {
            $services->onlyTrashed();
        }

        if ($request->download) {
            return (new ServicesExport($services))
                ->download(Str::slug(get_setting('app_name').'-services-export').'.xlsx');
        }

        $services = $services->paginate($per_page)
                                           ->appends($request->except('page'));

        return view('admin.services.index', compact('services', 'title', 'per_page', 'trashed'));
    }

    public function create(Request $request)
    {
        return view('admin.services.create', ['service' => new Service()]);
    }

    public function store(ServicesRequest $request)
    {
        $service = new Service($request->all());
        $service->save();

        $this->flashSuccessMessage();
        return to_route('admin.services.edit', $service);
    }

    public function show(Service $service)
    {
        return to_route('admin.services.edit', $service);
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(ServicesRequest $request, Service $service)
    {
        $service->fill($request->all());
        $service->save();

        $this->flashSuccessMessage();
        return to_route('admin.services.edit', $service);
    }

    public function destroy(Service $service, Request $request)
    {
        if (! $service->delete()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }
            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.services.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function trash(Request $request)
    {
        $this->authorize('trash', Service::class);
        return $this->index($request, true);
    }

    /**
     * @throws AuthorizationException
     */
    public function forceDelete($id, Request $request)
    {
        //find the model
        $field = with(new Service())->getRouteKeyName();
        $service = Service::onlyTrashed()
            ->where($field, $id)
            ->firstOrFail();

        //authorize
        $this->authorize('forceDelete', $service);

        //send error
        if (! $service->forceDelete()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }
            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.services.trash');
    }

    /**
     * @throws AuthorizationException
     */
    public function restore($id, Request $request)
    {
        //find the model
        $field = with(new Service())->getRouteKeyName();
        $service = Service::onlyTrashed()
            ->where($field, $id)
            ->firstOrFail();

        //authorize
        $this->authorize('restore', $service);

        //send error
        if (! $service->restore()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }

            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.services.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function bulk(Request $request)
    {
        $this->authorize('viewAny', Service::class);

        $this->validate($request, [
            'action' => 'required|in:delete',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
        ]);

        $action = $request->input('action');
        $ids = $request->input('services', []);

        switch ($action) {
            case 'delete':
                //make sure allowed to delete
                $this->authorize('delete_services');

                Service::whereIn('id', $ids)
                    ->get()
                    ->each(function (Service $service) {
                        $service->delete();
                    });
                break;
        }

        $this->flashSuccessMessage();
        return $this->redirect($request, to_route('admin.services.index'));
    }
}
