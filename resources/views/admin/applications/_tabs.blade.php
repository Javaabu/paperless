@php
    $curr_controller = remove_suffix('Controller', class_basename(current_controller()));

    $tabs = [
        [
            'title' => __('Application Details'),
            'admin_icon' => 'fa-duotone fa-file-edit',
            'public_icon' => 'fa-duotone fa-file-edit',
            'url' => $application->id ? route('admin.applications.edit', $application) : route('admin.applications.create'),
            'active' => if_route_pattern('admin.applications.create') || if_route_pattern('admin.applications.edit')
        ],
        [
            'title' => __('Documents'),
            'admin_icon' => 'fa-duotone fa-arrow-up-to-line',
            'public_icon' => 'fa-duotone fa-arrow-up-to-line',
            'url' => $application->id ? route('admin.applications.documents', $application) : '#',
            'active' => if_route_pattern('admin.applications.documents'),
            'disabled' => ! $application->id
        ],
        [
            'title' => __('Reviews'),
            'admin_icon' => 'fa-duotone fa-file-check',
            'public_icon' => 'fa-duotone fa-file-check',
            'url' => $application->id ? route('admin.applications.review', $application) : '#',
            'active' => if_route_pattern('admin.applications.review'),
            'disabled' => ! $application->id
        ],
    ];

@endphp

@component('admin.components.link-tabs', compact('tabs'))
@endcomponent
