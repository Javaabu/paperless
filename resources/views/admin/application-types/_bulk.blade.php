<?php
    $actions = [];

    if (auth()->user()->can('delete_application_types')) {
        $actions['delete'] = __('Delete');
    }
?>

@include('admin.components.bulk', ['actions' => $actions, 'model' => 'application_types'])