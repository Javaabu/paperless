<?php

namespace Javaabu\Paperless\Support\Builders;

use App\Models\FormField;
use App\Models\InstructorCourse;
use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Support\Components\Select;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;

class AcademyCourseBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public function render(?string $input = null, ?array $options = [], ?bool $multiple = false)
    {
        return Select::make($this->form_field->slug)
                     ->label($this->form_field->name)
                     ->markAsRequired($this->form_field->is_required)
                     ->multiple($multiple)
                     ->options($options)
                     ->state($input)
                     ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';
        $academy_instructor_individual_id = $request_data['academy_instructor'] ?? -1;

        return [
            'academy_course' => [
                $is_required,
                function ($attribute, $value, $fail) use ($academy_instructor_individual_id) {
                    $course_exists = InstructorCourse::where('course_id', $value)
                        ->whereHas('academyInstructor', function ($query) use ($academy_instructor_individual_id) {
                            $query->where('instructor_id', $academy_instructor_individual_id);
                        })
                        ->exists();

                    if (!$course_exists) {
                        $fail('The selected course is not assigned to the selected instructor or academy.');
                    }
                },
            ]
        ];
    }
}
