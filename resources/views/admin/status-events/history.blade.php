@php
    $status_events = $model?->statusEvents()
                           ->latest('event_at')
                           ->with('user', 'trackable')
                           ->get();
@endphp

@include('paperless::admin.status-events._table')
