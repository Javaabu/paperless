<?php
    $actions = [];

    if (auth()->user()->can('delete_services')) {
        $actions['delete'] = __('Delete');
    }
?>

@include('admin.components.bulk', ['actions' => $actions, 'model' => 'services'])