@if (in_array($application->applicationType->code, [
    \App\Application\Enums\ApplicationTypes::NewLicense,
    \App\Application\Enums\ApplicationTypes::AddCategory,
]))
    @php
        $exam_attempts = $application->examAttempts
                                     ->sortByDesc('attempt_number')
                                     ->loadMissing(['examiner'])
                                     ->loadCount([
                                         'examChecklistItems',
                                         'examChecklistItems as exam_checklist_items_passed_count' => function ($query) {
                                            $query->where('result', \App\Helpers\Enums\ExamResults::Pass->value);
                                        },
                                    ]);

        $exam = $application->exams->first();
    @endphp
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Exam Attempts') }}</h4>
        </div>
        @include('admin.exam-attempts._table', [
            'no_bulk' => true,
            'no_pagination' => true,
            'no_checkbox' => true,
        ])
    </div>

@endif
