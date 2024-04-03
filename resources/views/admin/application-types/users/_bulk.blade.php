<?php
$actions = [];

if (auth()->user()->can('update', $application_type)) {
    $actions['delete'] = __('Delete');
    $actions['active'] = __('Active');
    $actions['inactive'] = __('Deactivate');
}
?>

@include('admin.components.bulk', ['actions' => $actions, 'model' => 'document_types'])
