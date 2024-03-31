<div {{ $attributes->class(['row']) }}>
    <div class="col-md-12">
        <div class="card">
            @isset ($cardHeader)
                <div class="card-header">
                    {{ $cardHeader }}
                </div>
            @endisset
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>
        {{ $buttons ?? '' }}
    </div>
</div>

