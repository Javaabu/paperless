@if ($application->getAllPayments()->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __("Payments") }}</h3>
        </div>
        <x-admin.payment-information :payments="$application->getAllPayments()" />
    </div>
@endif


