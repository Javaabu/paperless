<?php

namespace Javaabu\Paperless\Domains\DocumentTypes;

use Illuminate\Http\Request;
use Javaabu\Helpers\Traits\HasOrderbys;
use Javaabu\Helpers\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

class DocumentTypesController extends Controller
{
    use HasOrderbys;

    public function getModelClass(): string
    {
        return config('paperless.models.document_type');
    }

    public function __construct()
    {
        $this->authorizeResource($this->getModelClass());
    }

    protected static function initOrderbys()
    {
        static::$orderbys = [
            'name'       => __('Name'),
            'created_at' => __('Created At'),
            'id'         => __('ID'),
        ];
    }

    public function index(Request $request, $trashed = false)
    {
        $title = __('All Document Types');
        $orderby = $this->getOrderBy($request, 'created_at');
        $order = $this->getOrder($request, 'created_at', $orderby);
        $per_page = $this->getPerPage($request);

        $document_types = $this->getModelClass()::orderBy($orderby, $order);

        if ($search = $request->input('search')) {
            $document_types->search($search);
            $title = __('Document Types matching \':search\'', ['search' => $search]);
        }

        if ($trashed) {
            $document_types->onlyTrashed();
        }

        $document_types = $document_types->paginate($per_page)
            ->appends($request->except('page'));

        return view('paperless::admin.document-types.index', compact('document_types', 'title', 'per_page', 'trashed'));
    }

    public function create(Request $request)
    {
        return view('paperless::admin.document-types.create', ['document_type' => new DocumentType()]);
    }

    public function store(DocumentTypesRequest $request)
    {
        $document_type = new DocumentType($request->all());
        $document_type->save();

        $this->flashSuccessMessage();

        return to_route('admin.document-types.edit', $document_type);
    }

    public function show(DocumentType $document_type)
    {
        return to_route('admin.document-types.edit', $document_type);
    }

    public function edit(DocumentType $document_type)
    {
        return view('paperless::admin.document-types.edit', compact('document_type'));
    }

    public function update(DocumentTypesRequest $request, DocumentType $document_type)
    {
        $document_type->fill($request->all());
        $document_type->save();

        $this->flashSuccessMessage();

        return to_route('admin.document-types.edit', $document_type);
    }

    public function destroy(DocumentType $document_type, Request $request)
    {
        if (! $document_type->delete()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }
            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.document-types.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function trash(Request $request)
    {
        $this->authorize('trash', DocumentType::class);

        return $this->index($request, true);
    }

    /**
     * @throws AuthorizationException
     */
    public function forceDelete($id, Request $request)
    {
        //find the model
        $field = with(new DocumentType())->getRouteKeyName();
        $document_type = DocumentType::onlyTrashed()
            ->where($field, $id)
            ->firstOrFail();

        //authorize
        $this->authorize('forceDelete', $document_type);

        //send error
        if (! $document_type->forceDelete()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }
            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.document-types.trash');
    }

    /**
     * @throws AuthorizationException
     */
    public function restore($id, Request $request)
    {
        //find the model
        $field = with(new DocumentType())->getRouteKeyName();
        $document_type = DocumentType::onlyTrashed()
            ->where($field, $id)
            ->firstOrFail();

        //authorize
        $this->authorize('restore', $document_type);

        //send error
        if (! $document_type->restore()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }

            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.document-types.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function bulk(Request $request)
    {
        $this->authorize('viewAny', DocumentType::class);

        $this->validate($request, [
            'action'           => 'required|in:delete',
            'document_types'   => 'required|array',
            'document_types.*' => 'exists:document_types,id',
        ]);

        $action = $request->input('action');
        $ids = $request->input('document_types', []);

        switch ($action) {
            case 'delete':
                //make sure allowed to delete
                $this->authorize('delete_document_types');

                DocumentType::whereIn('id', $ids)
                    ->get()
                    ->each(function (DocumentType $document_type) {
                        $document_type->delete();
                    });

                break;
        }

        $this->flashSuccessMessage();

        return $this->redirect($request, to_route('admin.document-types.index'));
    }
}
