<?php
$actions = [];

if (auth()->user()->can('update', $application_type)) {
    $actions['delete'] = __('Delete');
    $actions['auto_applied'] = __('Apply Automatically');
    $actions['not_auto_applied'] = __('Don\'t Apply Automatically');
}
?>

@include('admin.components.bulk', ['actions' => $actions, 'model' => 'services'])
