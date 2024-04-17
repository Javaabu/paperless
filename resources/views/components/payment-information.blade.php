@props([
    'payments' => [],
])

@php
    $payments->loadMissing('payer', 'payable', 'service');
@endphp

<table class="table table-bordered mb-0">
    <thead>
    <tr>
        <th data-sort-field="id" class="{{ add_sort_class('id') }}">{{ __('Payment #') }}</th>
        <th data-sort-field="created_at" class="{{ add_sort_class('created_at') }}">{{ __('Date') }}</th>
        <th>{{ __('Payer') }}</th>
        <th data-sort-field="amount" class="{{ add_sort_class('amount') }}">{{ __('Amount') }}</th>
        <th>{{ __('Status') }}</th>
    </tr>
    </thead>
    <tbody>
        @forelse ($payments as $payment)
            <tr>
                <td>{!! $payment->admin_link !!}</td>
                <td>{{ format_datetime($payment->created_at) }}</td>
                <td>{!! $payment->payer?->admin_link !!}</td>
                <td>{{ format_currency($payment->amount) }}</td>
                <td>
                    <x-badge :color="$payment->getStatusColor()">
                        {{ $payment->getStatusLabel() }} {{ $payment->paid_at?->format('d/m/Y H:i') }}
                    </x-badge>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">{{ __('No payment information available.') }}</td>
            </tr>
        @endforelse
    </tbody>
</table>
