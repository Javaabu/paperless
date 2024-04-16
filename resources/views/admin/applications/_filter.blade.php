@component('admin.components.filter')
    <div class="row mb-4">
        <div class="col-md-3">
            <x-admin.input-group for="search" :label="__('Search')" stacked>
                <x-admin.input-text name="search" :placeholder="__('Search...')"
                                    :value="request()->input('search', old('search'))"/>
            </x-admin.input-group>
        </div>
        <div class="col-md-3">
            <x-admin.input-group for="application_type" :label="__('Application Type')" stacked>
                <x-admin.select name="application_type" :options="\App\Models\ApplicationType::pluck('name', 'id')"
                                :value="request()->input('application_type')"/>
            </x-admin.input-group>
        </div>
        @php
            $options = \App\Models\EntityType::query()->select('name', 'id')->pluck('name', 'id')->prepend('', '');
            $individual_entity_id  = \App\Helpers\Enums\EntityTypeEnums::Individual->getEntityTypeId();
            $academy_entity_id  = \App\Helpers\Enums\EntityTypeEnums::Academy->getEntityTypeId();
            $agency_entity_id  = \App\Helpers\Enums\EntityTypeEnums::Agency->getEntityTypeId();
        @endphp
        <div class="col-md-3">
            <x-admin.input-group for="applicant_type" :label="__('Applicant Type')" stacked>
                <x-admin.select name="applicant_type"
                                select-child=".applicant-selector"
                                :value="request()->get('applicant_type')"
                                required
                                :placeholder="__('Select A Type')"
                                :options="$options"
                />
            </x-admin.input-group>
        </div>

        <div class="col-md-3">
            <x-admin.conditional-display-wrapper reference="#applicant_type"
                                                 :values="[$individual_entity_id, $academy_entity_id, $agency_entity_id]"
                                                 :reverse="true"
            >
                <x-admin.input-group for="empty_applicant"
                                     :label="__('Applicant')"
                                     stacked
                >
                    <x-admin.input-text name="applicant" :placeholder="__('Applicant')" :disabled="true" />
                </x-admin.input-group>
            </x-admin.conditional-display-wrapper>
            <x-admin.conditional-display-wrapper reference="#applicant_type"
                                                 :values="$individual_entity_id"
            >
                <x-admin.input-group for="individual_applicant"
                                     :label="__('Individual')"
                                     required
                                     stacked
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
                <x-admin.input-group for="academy_applicant" :label="__('Maritime Institute')" stacked
                >
                    @php $academy_selected = \App\Models\Entity::query()->where('entity_type_id', $academy_entity_id)->find(request()->input('applicant')); @endphp
                    <x-admin.child-selector
                        id="academy_applicant"
                        name="applicant"
                        extra-classes="applicant-selector"
                        name-field="formatted_name"
                        filter-field="applicant_type"
                        :hide-child="true"
                        :selected="$academy_selected"
                        :ajax-url="route('api.entities.index')"
                        :no-mb="true"
                    />
                </x-admin.input-group>
            </x-admin.conditional-display-wrapper>

            <x-admin.conditional-display-wrapper reference="#applicant_type"
                                                 :values="$agency_entity_id"
            >
                <x-admin.input-group for="agency_applicant" :label="__('Agent')" stacked
                >
                    @php $agent_selected = \App\Models\Entity::query()->where('entity_type_id', $agency_entity_id)->find(request()->input('applicant')); @endphp
                    <x-admin.child-selector
                        id="agency_applicant"
                        name="applicant"
                        extra-classes="applicant-selector"
                        name-field="formatted_name"
                        filter-field="applicant_type"
                        :hide-child="true"
                        :selected="$agent_selected"
                        :ajax-url="route('api.entities.index')"
                        :no-mb="true"
                    />
                </x-admin.input-group>
            </x-admin.conditional-display-wrapper>
        </div>

    </div>
    <div class="row">
        <div class="col-md-3">
            <x-admin.input-group for="status" :label="__('Status')" stacked>
                <x-admin.select name="status" :options="\App\Application\Enums\ApplicationStatuses::labels()"
                                :value="request()->input('status')"/>
            </x-admin.input-group>
        </div>
        <div class="col-md-3">
            @php
                $urgency_options = [
                    '' => __('All'),
                    'low' => __('Low'),
                    'medium' => __('Medium: Past Alert Time'),
                    'high' => __('High: Past ETA'),
                ];
            @endphp
            <x-admin.input-group for="urgency" :label="__('Urgency')" stacked>
                <x-admin.select name="urgency" :options="$urgency_options"
                                :value="request()->input('urgency')"/>
            </x-admin.input-group>
        </div>
        <div class="col-md-3">
            @include('admin.components.per-page')
        </div>
        <div class="col-md-3">
            @component('admin.components.filter-submit', [
                'filter_url' => if_route('admin.applications.trash') ? route('admin.applications.trash') : route('admin.applications.index'),
                'export' => true,
            ])
            @endcomponent
        </div>
    </div>
@endcomponent
