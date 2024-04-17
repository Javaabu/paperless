<?php

namespace Javaabu\Paperless\Domains\Applications;


use Illuminate\Contracts\View\View;
use Javaabu\Helpers\Http\Controllers\Controller;

class ApplicationViewsController extends Controller
{
    public function show(Application $application): View
    {
        return view('paperless::admin.applications.show.show', compact('application'));
    }

    public function details(Application $application)
    {
        return view('admin.applications.show.details', compact('application'));
    }

    public function viewDocuments(Application $application)
    {
        return view('admin.applications.show.view-documents', compact('application'));
    }

    public function history(Application $application)
    {
        return view('admin.applications.show.history', compact('application'));
    }
}
