<?php
    $actions = [];

    if (auth()->user()->can('delete_applications')) {
        $actions['delete'] = __('Delete');
    }
?>

@include('admin.components.bulk', ['actions' => $actions, 'model' => 'applications'])