<?php

namespace Javaabu\Paperless\Domains\Documents;

use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Helpers\Exceptions\AppException;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Javaabu\Paperless\Domains\Applications\Application;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Javaabu\Paperless\Domains\DocumentTypes\DocumentType;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;

class DocumentsController extends Controller
{
    use AuthorizesRequests;

    // public function __construct()
    // {
    //     $this->authorizeResource(config('paperless.models.media'), 'media');
    // }

    /**
     * Find the model from the request
     *
     * @param DocumentsRequest $request
     * @return Model
     */
    protected function findModel(DocumentsRequest $request): Model
    {
        $class = Model::getActualClassNameForMorph($request->input('model_type'));
        $model_id = $request->input('model_id');

        return (new $class())::find($model_id);
    }

    /**
     * Streams the document to user according to his/her permission
     *
     * @param Media $media
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function show(Media $media): BinaryFileResponse
    {
        $this->authorize('view', $media);

        return response()->file($media->getPath(), [
            'Content-Type'        => $media->mime_type,
            'Content-Disposition' => 'inline; filename="'.$media->file_name.'"',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  DocumentsRequest  $request
     * @return JsonResponse
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function store(DocumentsRequest $request): JsonResponse
    {
        // find the model
        $model = $this->findModel($request);
        // check whether the user can edit documents for the model
        $this->authorize('editDocuments', $model);

        // get the document type
        $document_type = null;
        if ($document_type_id = $request->input('document_type')) {
            $document_type = DocumentType::find($document_type_id);
        }

        // add file
        $document = null;
        if ($file = $request->file('file')) {
            if ($document_type && $model instanceof Application) {
                $model->getMedia('documents')->where('document_type_id', $document_type->id)->each(function ($media) {
                    $media->delete();
                });
            }

            $document = $model->addMedia($file);

            // set name
            if ($name = $request->input('name')) {
                $document->usingName($name);
            } elseif ($document_type) {
                $document->usingName($document_type->name);
            }

            // set custom properties
            $custom_properties = [];

            // set description
            if ($description = $request->input('description')) {
                $custom_properties = compact('description');
            }

            // set document type slug
            if ($document_type) {
                $custom_properties['document_type'] = $document_type->slug;
            }

            if ($custom_properties) {
                $document->withCustomProperties($custom_properties);
            }

            // for testing
            if (app()->runningUnitTests()) {
                $document->preservingOriginal();
            }

            $file = Str::slug(Str::random(8)) . '.'.$file->guessExtension();
            $document = $document->usingFileName($file)
                ->toMediaCollection('documents');
        }

        // check if it was added
        if (! $document) {
            throw new AppException(500, 'FileNotSaved', 'File could not be saved.');
        }
        // add the document type
        if ($document_type) {
            $document->documentType()->associate($document_type);
            $document->save();
        }

        return response()->json([
            'success'             => true,
            'id'                  => $document->id,
            'type_slug'           => $document->type_slug,
            'human_readable_size' => $document->human_readable_size,
            'icon'                => $document->icon,
            'web_icon'            => $document->web_icon,
            'extension'           => $document->extension,
            'name'                => $document->name,
            'file_name'           => $document->file_name,
            'url'                 => $document->getUrl(),
            'delete_url'          => $document->delete_url,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param DocumentsRequest $request
     * @param Media $document
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(DocumentsRequest $request, Media $document): JsonResponse
    {
        $this->authorize('update', $document);

        if ($request->filled('name')) {
            $document->name = $request->input('name');
        }

        if ($request->has('description')) {
            $document->setCustomProperty('description', $request->description);
        }

        $document->save();

        return response()->json($document);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Media $document
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Media $document): JsonResponse
    {
        $model = $document->model;
        $this->authorize('editDocuments', $model);

        if (! $document->delete()) {
            abort(500, 'Unable to delete the requested document.');
        }

        return response()->json(true);
    }
}
