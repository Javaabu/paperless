@php
    $curr_controller = remove_suffix('Controller', class_basename(current_controller()));

    $tabs = [
        [
            'title' => __('Details'),
            'admin_icon' => 'fa fa-duotone fa-circle-info',
            'public_icon' => 'align-left',
            'url' => $application_type->id ? route('admin.application-types.show', $application_type) : '#',
            'active' => if_route_pattern('admin.application-types.create') || if_route_pattern('admin.application-types.show') || if_route_pattern('admin.application-types.edit')
        ],
        [
            'title' => __('Services'),
            'admin_icon' => 'fa fa-duotone fa-credit-card',
            'public_icon' => 'fa fa-duotone fa-credit-card',
            'url' =>  $application_type->id ? route('admin.application-types.services.index', $application_type) : '#',
            'active' => if_route('admin.application-types.services.index'),
        ],
//        [
//            'title' => __('Document Types'),
//            'admin_icon' => 'fa fa-duotone fa-folder-open',
//            'public_icon' => 'users',
//            'url' =>  $application_type->id ? route('admin.application-types.document-types.index', $application_type) : '#',
//            'active' => if_route('admin.application-types.document-types.index'),
//        ],
//        [
//            'title' => __('Assigned Users'),
//            'admin_icon' => 'fa fa-duotone fa-user-tie',
//            'public_icon' => 'users',
//            'url' =>  $application_type->id ? route('admin.application-types.assigned-users.index', $application_type) : '#',
//            'active' => if_route('admin.application-types.assigned-users.index'),
//        ],
//        [
//            'title' => __('Form Fields'),
//            'admin_icon' => 'fa fa-duotone fa-pen-field',
//            'public_icon' => 'users',
//            'url' =>  $application_type->id ? route('admin.application-types.form-fields.index', $application_type) : '#',
//            'active' => if_route('admin.application-types.form-fields.index'),
//        ],
//         [
//            'title' => __('Stats'),
//            'admin_icon' => 'fa fa-duotone fa-chart-simple',
//            'public_icon' => 'fa fa-duotone fa-chart-simple',
//            'url' =>  $application_type->id ? route('admin.application-types.stats', $application_type) : '#',
//            'active' => if_route('admin.application-types.stats'),
//        ],
    ];

@endphp

@component('paperless::components.link-tabs', compact('tabs'))
@endcomponent
