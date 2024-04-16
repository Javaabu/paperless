<x-admin.form-container with-floating-submit>

    @php
        $options = \App\Models\EntityType::query()->select('name', 'id')->pluck('name', 'id')->prepend('', '');
        $entity_types  = \App\Models\EntityType::all();
        $individual_entity_id  = \App\Helpers\Enums\EntityTypeEnums::Individual->getEntityTypeId();
        $academy_entity_id  = \App\Helpers\Enums\EntityTypeEnums::Academy->getEntityTypeId();
        $agency_entity_id  = \App\Helpers\Enums\EntityTypeEnums::Agency->getEntityTypeId();
    @endphp
    <x-admin.input-group for="applicant_type"
                         :label="__('On behalf of whom are you applying?')"
                         required
    >
        <x-admin.select name="applicant_type"
                        select-child=".applicant-selector"
                        :value="request()->get('applicant_type')"
                        required
                        :placeholder="__('Select A Type')"
                        :options="$options"
        />
    </x-admin.input-group>


    <x-admin.conditional-display-wrapper reference="#applicant_type"
                                         :values="$individual_entity_id"
    >
        <x-admin.input-group for="individual_applicant"
                             required
        >
            @php $selected = \App\Models\Individual::query()->find(request()->input('applicant')); @endphp
            <x-admin.selector
                name-field="formatted_name"
                id="individual_applicant"
                required
                name="applicant"
                :selected="$selected"
                :ajax-url="route('api.individuals.index')"
            />
        </x-admin.input-group>
    </x-admin.conditional-display-wrapper>

    <x-admin.conditional-display-wrapper reference="#applicant_type"
                                         :values="$academy_entity_id"
    >
        <x-admin.input-group for="academy_applicant"
        >
            @php $selected = \App\Models\Entity::query()->where('entity_type_id', $academy_entity_id)->find(request()->input('applicant')); @endphp
            <x-admin.child-selector
                id="academy_applicant"
                name="applicant"
                extra-classes="applicant-selector"
                name-field="formatted_name"
                filter-field="applicant_type"
                :hide-child="true"
                :selected="$selected"
                :ajax-url="route('api.entities.index')"
            />
        </x-admin.input-group>
    </x-admin.conditional-display-wrapper>

    <x-admin.conditional-display-wrapper reference="#applicant_type"
                                         :values="$agency_entity_id"
    >
        <x-admin.input-group for="agency_applicant"
        >
            @php $selected = \App\Models\Entity::query()->where('entity_type_id', $agency_entity_id)->find(request()->input('applicant')); @endphp
            <x-admin.child-selector
                id="agency_applicant"
                name="applicant"
                extra-classes="applicant-selector"
                name-field="formatted_name"
                filter-field="applicant_type"
                :hide-child="true"
                :selected="$selected"
                :ajax-url="route('api.entities.index')"
            />
        </x-admin.input-group>
    </x-admin.conditional-display-wrapper>


    <x-admin.input-group for="application_type"
                         :label="__('For which service are you applying?')"
                         required
    >
        @php
            $selected = \App\Models\ApplicationType::query()->find(request()->input('application_type'));
        @endphp
        <x-admin.child-selector
            required
            extra-classes="applicant-selector"
            filter-field="applicant_type"
            :hide-child="true"
            name="application_type"
            :placeholder="__('Select A Application Type')"
            :selected="$selected"
            :ajax-url="route('api.application-types.index')"
        />
    </x-admin.input-group>


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

</x-admin.form-container>
