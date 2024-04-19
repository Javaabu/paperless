@foreach($status_events as $status_event)
    <tr>
        <td data-col="{{ __('Date') }}">
            <abbr title="{{ optional($status_event->event_at)->format('j M Y H:i') }}">
                {{ optional($status_event->event_at)->diffForHumans() }}
            </abbr>
        </td>

        <td data-col="{{ __('Status') }}">
            @php $color = $status_event->trackable?->getStatusColor($status_event->status) ?? 'primary'; @endphp
            <x-paperless::badge :color="$color">
                {{ $status_event->trackable?->getStatusLabel($status_event->status) ?? '' }}
            </x-paperless::badge>
        </td>

        <td data-col="{{ __('User') }}">
            @if($user = $status_event->user)
                {!! $user->admin_link !!}
            @else
                -
            @endif
        </td>

        <td data-col="{{ __('Remarks') }}">
            @if($remarks = $status_event->remarks)
                {!! nl2br(e($remarks)) !!}
            @else
                -
            @endif
        </td>
    </tr>
@endforeach
