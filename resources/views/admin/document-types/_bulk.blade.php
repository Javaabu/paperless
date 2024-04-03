<?php
    $actions = [];

    if (auth()->user()->can('delete_document_types')) {
        $actions['delete'] = __('Delete');
    }
?>

<x-forms::bulk-actions :actions="$actions" model="document_types" />
