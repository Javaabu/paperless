@php
    $curr_controller = remove_suffix('Controller', class_basename(current_controller()));

    $tabs = [
        [
            'title' => __('Summary'),
            'admin_icon' => 'fa-duotone fa-clipboard-list',
            'public_icon' => 'fa-duotone fa-clipboard-list',
            'url' => route('admin.applications.show', $application),
            'active' => if_route_pattern('admin.applications.show'),
        ],
        [
            'title' => __('Details'),
            'admin_icon' => 'fa-duotone fa-square-list',
            'public_icon' => 'fa-duotone fa-square-list',
            'url' => route('admin.applications.details', $application),
            'active' => if_route_pattern('admin.applications.details'),
        ],
        [
            'title' => __('Documents'),
            'admin_icon' => 'fa-duotone fa-copy',
            'public_icon' => 'fa-duotone fa-copy',
            'url' => route('admin.applications.view-documents', $application),
            'active' => if_route_pattern('admin.applications.view-documents'),
        ],
        [
            'title' => __('History'),
            'admin_icon' => 'fa-duotone fa-clock-rotate-left',
            'public_icon' => 'fa-duotone fa-clock-rotate-left',
            'url' => route('admin.applications.history', $application),
            'active' => if_route_pattern('admin.applications.history'),
        ],
    ];

@endphp

@component('paperless::components.link-tabs', compact('tabs'))
@endcomponent
