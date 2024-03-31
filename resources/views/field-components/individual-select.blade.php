@php
    $countries = \App\Models\Country::all();
    $maldives = $countries->where('code', 'mv')->first();
    $countries = $countries->pluck('name', 'id')->toArray();
    $selected_value = old('nationality', $getIndividual()?->nationality_id);


    $name_id = $name_name = 'name';
    $nationality_id = $nationality_name  = 'nationality';
    $name_dv_id = $name_dv_name  = 'name_dv';
    $gov_id_id = $gov_id_name  = 'gov_id';
    $gender_id = $gender_name  = 'gender';

    $repeating_instance = $getRepeatingInstance();

    // selected values
    $state_array = $getState();
    $selected_name = old('name', $getIndividual()?->name);
    $selected_name_dv = old('name_dv', $getIndividual()?->name_dv);
    $selected_gov_id = old('gov_id', $getIndividual()?->gov_id);
    $selected_gender = old('gender', $getIndividual()?->gender?->value);

    if (data_get($state_array, 'name')) {
        $selected_name = data_get($state_array, 'name');
    }

    if (data_get($state_array, 'name_dv')) {
        $selected_name_dv = data_get($state_array, 'name_dv');
    }

    if (data_get($state_array, 'gov_id')) {
        $selected_gov_id = data_get($state_array, 'gov_id');
    }

    if (data_get($state_array, 'gender')) {
        $selected_gender = data_get($state_array, 'gender');
    }

    if ($isRepeatable()) {
        $name_id .= "_{$repeating_instance}";
        $nationality_id .= "_{$repeating_instance}";
        $name_dv_id .= "_{$repeating_instance}";
        $gov_id_id .= "_{$repeating_instance}";
        $gender_id .= "_{$repeating_instance}";

        $repeating_group_id = $getRepeatingGroupId();
        $name_name = "{$repeating_group_id}[{$repeating_instance}][$name_name]";
        $nationality_name = "{$repeating_group_id}[{$repeating_instance}][$nationality_name]";
        $name_dv_name = "{$repeating_group_id}[{$repeating_instance}][$name_dv_name]";
        $gov_id_name = "{$repeating_group_id}[{$repeating_instance}][$gov_id_name]";
        $gender_name = "{$repeating_group_id}[{$repeating_instance}][$gender_name]";
    }

@endphp

<x-paperless::input-group for="{{ $name_id }}" :label="__('Name')" :required="true">
    <x-paperless::text-input id="{{ $name_id }}" :name="$name_name" :required="true" :placeholder="__('Individual Name')" :value="$selected_name" />
</x-paperless::input-group>

<x-paperless::input-group for="{{ $nationality_id }}" :label="__('Nationality')" :required="true">
    <x-paperless::select id="{{ $nationality_id }}" :name="$nationality_name"
                         :options="$countries" :required="true" :value="$selected_value" :placeholder="__('Select a country')"/>
</x-paperless::input-group>

<x-paperless::conditional-display-wrapper :with_wrapper="true" reference="#{{ $nationality_id }}" :reverse="true" :values="json_encode([$maldives->id])">
    <x-paperless::input-group for="{{ $gov_id_id }}" :label="__('Name in Dhivehi')" :required="true">
        <x-paperless::text-input id="{{ $name_dv_id }}" :name="$name_dv_name" :dv="true" :required="true" :placeholder="__('ނަން')" :value="$selected_name_dv" />
    </x-paperless::input-group>
</x-paperless::conditional-display-wrapper>

<x-paperless::conditional-display-wrapper :with_wrapper="true" reference="#{{ $nationality_id }}" :reverse="true" :values="json_encode([$maldives->id])">
    <x-paperless::input-group for="{{ $gov_id_id }}" :label="__('Work Permit Number')" :required="true">
        <x-paperless::text-input id="{{ $gov_id_id }}" :name="$gov_id_name" :required="true" :placeholder="__('Government Id')" :value="$selected_gov_id" />
    </x-paperless::input-group>
</x-paperless::conditional-display-wrapper>

<x-paperless::conditional-display-wrapper :with_wrapper="true" reference="#{{ $nationality_id }}" :values="json_encode([$maldives->id])">
    <x-paperless::input-group for="{{ $gov_id_id }}" :label="__('National ID')" :required="true">
        <x-paperless::text-input id="{{ $gov_id_id }}" :name="$gov_id_name" :required="true" :placeholder="__('Government Id')" :value="$selected_gov_id" />
    </x-paperless::input-group>
</x-paperless::conditional-display-wrapper>

<x-paperless::conditional-display-wrapper :with_wrapper="true" reference="#{{ $nationality_id }}" :reverse="true" :values="json_encode([$maldives->id])">
    <x-paperless::input-group for="{{ $gender_id }}" :label="__('Gender')" :required="true">
        @php $genders = ['' => ''] + \App\Helpers\Enums\Genders::labels(); @endphp
        <x-paperless::select id="{{ $gender_id }}" :name="$gender_name" :options="$genders" :required="true" :placeholder="__('Select a gender')" :value="$selected_gender" />
    </x-paperless::input-group>
</x-paperless::conditional-display-wrapper>

