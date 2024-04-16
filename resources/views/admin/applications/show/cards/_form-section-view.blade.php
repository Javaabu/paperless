
<div class="card">
    <div class="card-header">
        <div class="card-title">{{ $section->name }}</div>
        <div class="card-subtitle">{{ $section->description }}</div>
    </div>
    <div class="card-body">
        {!! $section->renderInfoList($application->applicant, $section_form_inputs, false) !!}
    </div>
</div>
