<?php

namespace Javaabu\Paperless\Domains\Applications;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Helpers\Traits\HasOrderbys;
use Illuminate\Support\Facades\Validator;
use Javaabu\Helpers\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Javaabu\Paperless\Domains\EntityTypes\EntityType;

class ApplicationsController extends Controller
{
    use HasOrderbys;

    public function getModelClass(): string
    {
        return config('paperless.models.application');
    }

    public function __construct()
    {
        //        $this->authorizeResource(config('paperless.application_model'));
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
        $title = __('All Applications');
        $orderby = $this->getOrderBy($request, 'created_at');
        $order = $this->getOrder($request, 'created_at', $orderby);
        $per_page = $this->getPerPage($request);

        $applications = $this->getModelClass()::orderBy($orderby, $order)->userVisible();

        if ($search = $request->input('search')) {
            $applications->search($search);
            $title = __('Applications matching \':search\'', ['search' => $search]);
        }

        if ($status = $request->input('status')) {
            $applications->where('status', $status);
        }

        if ($application_type = $request->input('application_type')) {
            $applications->where('application_type_id', $application_type);
        }

        if ($applicant_type = $request->input('applicant_type')) {
            $entity_slug = EntityType::find($applicant_type)->slug;
            $applications->where('applicant_type', $entity_slug);

            if ($applicant = $request->input('applicant')) {
                $applications->where('applicant_id', $applicant);
            }
        }

        if ($urgency = $request->input('urgency')) {
            $applications->ongoing();
            if ($urgency == 'high') {
                $applications->where('eta_at', '<', now());
            } elseif ($urgency == 'medium') {
                $applications->where('alert_at', '<', now())->where('eta_at', '>', now());
            } else {
                $applications->where('alert_at', '>', now());
            }
        }

        if ($trashed) {
            $applications->onlyTrashed();
        }

        if ($request->download) {
            return (new ApplicationsExport($applications))
                ->download(Str::slug(get_setting('app_name') . '-applications-export') . '.xlsx');
        }

        $applications = $applications
            ->with([
                'applicationType',
                'generated',
                'applicant',
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($per_page)
            ->appends($request->except('page'));


        return view('paperless::admin.applications.index', compact('applications', 'title', 'per_page', 'trashed'));
    }

    public function create(Request $request)
    {
        $application_class = $this->getModelClass();

        if (! $request->hasAny([
            'application_type',
            'applicant_type',
            'applicant',
        ])) {
            return view('paperless::admin.applications.initiate', [
                'application' => new $application_class(),
                'initialize'  => true,
            ]);
        }

        $rules = [
            'applicant_type'   => ['required', 'exists:entity_types,id'],
            'applicant'        => ['required', 'exists:users,id'],
            'application_type' => [
                'required',
                'exists:application_types,id',
                'exists:entity_type_application_type,application_type_id,entity_type_id,' . $request->input('applicant_type')],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return view('paperless::admin.applications.initiate', ['application' => new $application_class()])->withErrors($validator);
        }

        $application_type = config('paperless.models.application_type')::find($request->input('application_type'));
        $applicant_model_class = config('paperless.models.user');
        $applicant = $applicant_model_class::find($request->input('applicant'));

        return view('paperless::admin.applications.create', [
            'application'      => new $application_class(),
            'applicant'        => $applicant,
            'application_type' => $application_type,
        ]);

    }

    public function store(ApplicationsRequest $request)
    {
        $application_class = config('paperless.models.application');

        $application = new $application_class();
        $application->applicant()->associate($request->getApplicant());
        $application->applicationType()->associate($request->input('application_type_id'));
        $application->save();

        $input_data_array = collect($request->validated())->except([
            'applicant_type_id',
            'applicant_id',
            'application_type_id',
        ])->toArray();

        $application->updateFormInputs($input_data_array);

        $status_enum = config('paperless.enums.application_status');
        $application->createStatusEvent(
            $status_enum::Draft->value,
            $status_enum::Draft->getRemarks()
        );

        $this->flashSuccessMessage();
        return to_route('admin.applications.documents', $application);
    }

    public function edit(Application $application)
    {
        $application_type = $application->applicationType;
        $applicant = $application->applicant;
        return view('admin.applications.edit', compact('application', 'application_type', 'applicant'));
    }

    public function update(ApplicationsUpdateRequest $request, Application $application)
    {
        $application->updateFormInputs($request->validated());
        $this->flashSuccessMessage();
        return to_route('admin.applications.documents', $application);
    }

    public function adminSectionUpdate(Application $application, FormSection $form_section, ApplicationAdminSectionsUpdateRequest $request)
    {
        $form_fields = $form_section->formFields->whereNull('field_group_id');
        foreach ($form_fields as $form_field) {
            $form_field->getBuilder()->saveInputs($application, $form_field, $request->validated());
        }

        $this->flashSuccessMessage();
        return to_route('admin.applications.show', $application);
    }

    public function destroy(Application $application, Request $request)
    {
        if (! $application->delete()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }
            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.applications.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function trash(Request $request)
    {
        $this->authorize('trash', Application::class);
        return $this->index($request, true);
    }

    /**
     * @throws AuthorizationException
     */
    public function forceDelete($id, Request $request)
    {
        //find the model
        $field = with(new Application())->getRouteKeyName();
        $application = Application::onlyTrashed()
                                  ->where($field, $id)
                                  ->firstOrFail();

        //authorize
        $this->authorize('forceDelete', $application);

        //send error
        if (! $application->forceDelete()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }
            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.applications.trash');
    }

    /**
     * @throws AuthorizationException
     */
    public function restore($id, Request $request)
    {
        //find the model
        $field = with(new Application())->getRouteKeyName();
        $application = Application::onlyTrashed()
                                  ->where($field, $id)
                                  ->firstOrFail();

        //authorize
        $this->authorize('restore', $application);

        //send error
        if (! $application->restore()) {
            if ($request->expectsJson()) {
                return response()->json(false, 500);
            }

            abort(500);
        }

        if ($request->expectsJson()) {
            return response()->json(true);
        }

        return to_route('admin.applications.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function bulk(Request $request)
    {
        $this->authorize('viewAny', Application::class);

        $this->validate($request, [
            'action'         => 'required|in:delete',
            'applications'   => 'required|array',
            'applications.*' => 'exists:applications,id',
        ]);

        $action = $request->input('action');
        $ids = $request->input('applications', []);

        switch ($action) {
            case 'delete':
                //make sure allowed to delete
                $this->authorize('delete_applications');

                Application::whereIn('id', $ids)
                           ->get()
                           ->each(function (Application $application) {
                               $application->delete();
                           });
                break;
        }

        $this->flashSuccessMessage();
        return $this->redirect($request, to_route('admin.applications.index'));
    }

    public function documents(Application $application, Request $request): View
    {
        $this->authorize('update', $application);

        $required_documents = $application->applicationType->documentTypes;
        $documents = $application->getMedia('documents');
        //        dd($documents);

        return view('paperless::admin.applications.documents', compact(
            'application',
            'documents',
            'required_documents',
        ));
    }

    public function review(Application $application, Request $request)
    {
        $this->authorize('update', $application);

        return view('admin.applications.review', compact('application'));
    }

    public function statusUpdate(Application $application, Request $request)
    {

        $allowed_actions = ApplicationStatuses::getAllowedActions(request()->user(), $application);
        $request->validate([
            'action'  => [
                'required',
                'in:' . implode(',', $allowed_actions),
            ],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $remarks = $request->input('remarks');
        $application->statusAction()->{$request->input('action')}($remarks);
        $this->flashSuccessMessage('Application status updated successfully');
        return to_route('admin.applications.show', $application);
    }

    public function assignStaff(Application $application, Request $request)
    {
        $this->authorize('assignUser', $application);

        $request->validate([
            'assign_user' => ['required', 'exists:users,id'],
        ]);

        $application->assignedTo()->associate($request->input('assign_user'));
        $application->save();

        ApplicationStaffAssignedJob::dispatch($application->refresh());

        $this->flashSuccessMessage('Application assigned to staff successfully');
        return to_route('admin.applications.show', $application);
    }

    public function receipt(Application $application, Request $request)
    {
        $this->authorize('view', $application);

        $application_receipt_name = 'application-receipt-' . $application->id . '.pdf';
        return response()->streamDownload(
            function () use ($application) {
                echo $application->receipt();
            },
            $application_receipt_name,
            ['Content-Type' => 'application/pdf']
        );
    }
}
