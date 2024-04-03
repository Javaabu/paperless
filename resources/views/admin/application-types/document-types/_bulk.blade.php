<?php
$actions = [];

if (auth()->user()->can('update', $application_type)) {
    $actions['delete'] = __('Delete');
    $actions['required'] = __('Required');
    $actions['not_required'] = __('Not Required');
}
?>

@include('admin.components.bulk', ['actions' => $actions, 'model' => 'document_types'])
