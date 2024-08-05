<x-paperless::conditional-wrapper
    :is-conditional="$isConditional()"
    :is-checkbox="$isConditionalCheckbox()"
    enable-elem="#{{ $getConditionalOn() }}"
    :value="$getConditionalValue()"
    :hide-fields="$isReversedConditional()"
>
    <x-forms::select2
        :name="$getName()"
        :options="$getOptions()"
        :default="$getSelected()"
        :ajax-url="$getApiUrl()"
        :filter-field="$getFilterBy() ? $getFilterBy() : 'name'"
        :name-field="$getNameField()"
        :required="$isMarkedAsRequired()"
        :placeholder="$getPlaceholder() ?? ''"
        :child="$getChild()"
        inline
    />
</x-paperless::conditional-wrapper>
