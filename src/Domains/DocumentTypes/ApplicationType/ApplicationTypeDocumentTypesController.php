<?php

namespace Javaabu\Paperless\Domains\DocumentTypes\ApplicationType;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Javaabu\Helpers\Traits\HasOrderbys;
use Javaabu\Helpers\Http\Controllers\Controller;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class ApplicationTypeDocumentTypesController extends Controller
{
    use HasOrderbys;

    protected static function initOrderbys()
    {
        static::$orderbys = [
            'name'       => __('Name'),
            'created_at' => __('Created At'),
            'id'         => __('ID'),
        ];
    }

    public function index(ApplicationType $application_type, Request $request)
    {
        $title = __('All Application Types');
        $orderby = $this->getOrderBy($request, 'created_at');
        $order = $this->getOrder($request, 'created_at', $orderby);
        $per_page = $this->getPerPage($request);

        $document_types = $application_type->documentTypes()->orderBy($orderby, $order);

        if ($search = $request->input('search')) {
            $document_types->search($search);
            $title = __('Application Types matching \':search\'', ['search' => $search]);
        }

        $document_types = $document_types->paginate($per_page)
                                         ->appends($request->except('page'));

        return view('paperless::admin.application-types.document-types.index', compact('application_type', 'document_types', 'title', 'per_page'));
    }

    public function show(ApplicationType $application_type): RedirectResponse
    {
        return to_route('admin.application-types.edit', $application_type);
    }

    public function store(ApplicationType $application_type, ApplicationTypeDocumentTypeRequest $request): RedirectResponse
    {
        $this->authorize('update', $application_type);
        $document_type = $request->input('document_type');
        $is_required = $request->input('is_required', false);

        $application_type->documentTypes()->attach($document_type, ['is_required' => $is_required]);

        $this->flashSuccessMessage();
        return to_route('admin.application-types.document-types.index', $application_type);
    }

    public function destroy(ApplicationType $application_type, DocumentType $document_type, Request $request): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $application_type);
        $application_type->documentTypes()->detach($document_type);

        if ($request->expectsJson()) {
            return response()->json(true, 204);
        }

        $this->flashSuccessMessage();
        return to_route('admin.application-types.document-types.index', $application_type);
    }

    public function bulk(ApplicationType $application_type, Request $request)
    {
        $this->authorize('view', $application_type);

        $this->validate($request, [
            'action'           => 'required|in:delete,required,not_required',
            'document_types'   => 'required|array',
            'document_types.*' => 'exists:document_type_application_type,id,application_type_id,' . $application_type->id,
        ]);

        $action = $request->input('action');
        $ids = $request->input('document_types', []);

        switch ($action) {
            case 'delete':
                $this->authorize('update', $application_type);
                $application_type->documentTypes()
                                 ->whereIn('document_type_application_type.id', $ids)
                                 ->each(function ($document_type) {
                                     $document_type->pivot->delete();
                                 });

                break;
            case 'required':
                $this->authorize('update', $application_type);
                $application_type->documentTypes()
                                 ->whereIn('document_type_application_type.id', $ids)
                                 ->each(function ($document_type) {
                                     $document_type->pivot->is_required = true;
                                     $document_type->pivot->save();
                                 });
                break;

            case 'not_required':
                $this->authorize('update', $application_type);
                $application_type->documentTypes()
                                 ->whereIn('document_type_application_type.id', $ids)
                                 ->each(function ($document_type) {
                                     $document_type->pivot->is_required = false;
                                     $document_type->pivot->save();
                                 });
                break;
        }

        $this->flashSuccessMessage();
        return to_route('admin.application-types.document-types.index', $application_type);
    }
}
