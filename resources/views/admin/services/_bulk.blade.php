<?php
    $actions = [];

    if (auth()->user()->can('delete_services')) {
        $actions['delete'] = __('Delete');
    }
?>

<x-forms::bulk-actions :actions="$actions" model="services" />
