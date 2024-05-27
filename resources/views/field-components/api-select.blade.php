@if($isConditional())
    <x-forms::conditional-wrapper reference="#{{ $getConditionalOn() }}" :values="$getConditionalValue()">
        <x-forms::select2
            :name="$getName()"
            :options="$getOptions()"
            :default="$getSelected()"
            :ajax-url="$getApiUrl()"
            :filter-field="$getFilterBy()"
            :name-field="$getNameField()"
            :required="$isMarkedAsRequired()"
            :placeholder="$getPlaceholder() ?? ''"
            :child="$getChild()"
            inline
        />
    </x-forms::conditional-wrapper>
@else
    <x-forms::select2
        :name="$getName()"
        :options="$getOptions()"
        :default="$getSelected()"
        :ajax-url="$getApiUrl()"
        :filter-field="$getFilterBy()"
        :name-field="$getNameField()"
        :required="$isMarkedAsRequired()"
        :placeholder="$getPlaceholder() ?? ''"
        :child="$getChild()"
        inline
    />
@endif
