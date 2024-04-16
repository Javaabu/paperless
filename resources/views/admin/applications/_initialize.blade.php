<x-forms::card>
    @php
        $options = config('paperless.models.entity_type')::query()->select('name', 'id')->pluck('name', 'id');
        $entity_types  = config('paperless.models.entity_type')::all();
        $individual_entity_id  = config('paperless.entity_type_enum')::User->getEntityTypeId();
    @endphp

    <x-forms::select2 name="applicant_type"
                      :label="__('On behalf of whom are you applying?')"
                      :required="true"
                      :inline="true"
                      :options="$options"/>


    <x-forms::conditional-wrapper reference="#applicant_type"
                                  :values="$individual_entity_id">
        <x-forms::select2
            name="applicant"
            :is-ajax="true"
            label=""
            :ajax-url="route('api.users.index')"
            name-field="name"
            :relation="true"
            :required="true"
            :inline="true"
            ajax-child="#application_type"
        />
    </x-forms::conditional-wrapper>

    <x-forms::select2 name="application_type"
                      :label="__('For which service are you applying?')"
                      :placeholder="__('Select A Application Type')"
                      :required="true"
                      :inline="true"
                      filter-field="applicant_type"
                      :hide-child="true"
                      :is-ajax="true"
                      :ajax-url="route('api.application-types.index')"
    />

    {{--    <x-admin.input-group for="application_type"--}}
    {{--                         :label="__('For which service are you applying?')"--}}
    {{--                         required--}}
    {{--    >--}}
    {{--        @php--}}
    {{--            $selected = \App\Models\ApplicationType::query()->find(request()->input('application_type'));--}}
    {{--        @endphp--}}
    {{--        <x-admin.child-selector--}}
    {{--            required--}}
    {{--            extra-classes="applicant-selector"--}}
    {{--            filter-field="applicant_type"--}}
    {{--            :hide-child="true"--}}
    {{--            name="application_type"--}}
    {{--            :placeholder="__('Select A Application Type')"--}}
    {{--            :selected="$selected"--}}
    {{--            :ajax-url="route('api.application-types.index')"--}}
    {{--        />--}}
    {{--    </x-admin.input-group>--}}


    <x-slot:buttons>
        <div class="button-group inline-btn-group">
            <button type="submit" class="btn btn-success btn--icon-text btn--raised">
                {{ __('Continue') }}<i class="ml-2 zmdi zmdi-arrow-right"></i>
            </button>
            <a class="btn btn-dark btn--icon-text"
               href="{{ route('admin.applications.index') }}"
            >
                <i class="zmdi zmdi-close"></i> {{__('Cancel') }}
            </a>
        </div>
    </x-slot:buttons>

</x-forms::card>
