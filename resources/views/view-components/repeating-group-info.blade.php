<div>
    @php
        $headings = $getFieldGroup()->formFields->pluck('name')->toArray();
        $form_input_rows = $getFormInputs()->groupBy('group_instance_number');
        $student_field_id = \App\Models\FormField::where('slug', 'student')->first()->id;
        $student_ids = $form_input_rows->flatten()->where('form_field_id', $student_field_id)->pluck('value');
        $students = \App\Models\Individual::whereIn('id', $student_ids)->with('nationality')->get();
    @endphp
    <table class="table table-bordered mb-0">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ __('Student Name') }}</th>
            <th>{{ __('Student Name Dv') }}</th>
            <th>{{ __('Nationality') }}</th>
            <th>{{ __('Government ID') }}</th>
            <th>{{ __('Gender') }}</th>
            <th>{{ __('Certificate') }}</th>
        </tr>
        </thead>
        <tbody>
            @foreach ($form_input_rows as $form_input_row)
                <tr>
                    <th scope="row">{{ $loop->index + 1 }}</th>
                    @foreach ($getFieldGroup()->formFields as $form_field)
                        @php
                            $form_input = $form_input_row->where('form_field_id', $form_field->id)->first();
                        @endphp
                        @if($form_field->slug === 'student')
                            @php
                                $student = $students->where('id', $form_input?->value)->first();
                            @endphp
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->name_dv }}</td>
                            <td>{{ $student->nationality?->name }}</td>
                            <td>{{ $student->gov_id }}</td>
                            <td>{{ $student->gender?->getLabel() }}</td>
                        @else
                            <td>{{ $form_input?->value }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
