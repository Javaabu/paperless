
<div class="card mb-4">
    <div class="card-body d-flex align-items-start" style="padding:15px;">
        <div>
            <span class="fa-duotone fa-{{ $form_field->type?->getIcon() }} mr-2"
                  style="background-color:#F7F7F7;padding:10px 12px;"></span>
        </div>
        <div class="">
            <div>{{ $form_field->name }} {{ $form_field->is_required ? '*' : '' }}</div>
            <div class="text-muted">{{ $form_field->placeholder }}</div>
            <div style="font-size: 0.9rem;" class="text-muted">{{ $form_field->slug }}</div>
            @if ($form_field->builder->getSlug() == 'select')
                <div>
                    <div>{{ __("Options") }}</div>
                    <ul>
                        @foreach ($form_field->options as $value => $label)
                            <li>{{ $label }} ({{ $value }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
