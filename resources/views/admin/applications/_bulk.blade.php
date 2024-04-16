<?php
    $actions = [];

    if (auth()->user()->can('delete_applications')) {
        $actions['delete'] = __('Delete');
    }
?>

<x-forms::bulk-actions :actions="$actions" model="applications" />
