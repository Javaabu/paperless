<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Models\FieldGroup;
use Javaabu\Paperless\Models\FormSection;
use Javaabu\Paperless\Support\ValueObjects\FieldDefinition;
use Javaabu\Paperless\Support\ValueObjects\SectionDefinition;
use Javaabu\Paperless\Support\ValueObjects\FieldGroupDefinition;

class CreateFormFieldsAction
{
    public function handle(ApplicationType $application_type, array $application_definition): void
    {
        $section_order = 1;

        foreach ($application_definition as $section_definition) {
            /* @var SectionDefinition $section_definition */
            $form_section = $this->seedSection($application_type, $section_definition, $section_order);

            $field_group_definitions = $section_definition->getFieldGroups();
            $group_order = 1;
            foreach ($field_group_definitions as $field_group_definition) {
                /* @var FieldGroupDefinition $field_group_definition */
                $field_group = $this->seedFieldGroup($application_type, $form_section, $field_group_definition, $group_order);

                $field_definitions = $field_group_definition->getFields();
                $field_order = 1;
                foreach ($field_definitions as $field_definition) {
                    /* @var FieldDefinition $field_definition */
                    $this->seedField($application_type, $form_section, $field_definition, $field_group, $field_order);
                    $field_order++;
                }

                $group_order++;
            }

            $field_definitions = $section_definition->getFields();
            $field_order = 1;
            foreach ($field_definitions as $field_definition) {
                /* @var FieldDefinition $field_definition */
                $this->seedField($application_type, $form_section, $field_definition, null, $field_order);
                $field_order++;
            }

            $section_order++;
        }
    }

    public function seedSection(
        ApplicationType   $application_type,
        SectionDefinition $section_definition,
        int|null          $section_order = null
    ): FormSection {
        return FormSection::updateOrCreate([
            'slug' => $section_definition->getSlug(),
        ], [
            'order_column'        => $section_order,
            'name'                => $section_definition->getLabel(),
            'description'         => $section_definition->getDescription(),
            'application_type_id' => $application_type->id,
            'is_admin_section'    => $section_definition->getIsAdminSection(),
        ]);
    }

    public function seedFieldGroup(
        ApplicationType      $application_type,
        FormSection          $form_section,
        FieldGroupDefinition $field_group_definition,
        int|null             $group_order = null
    ): FieldGroup {
        $meta = [
            'add_more_button' => $field_group_definition->getAddMoreButton(),
        ];

        return FieldGroup::updateOrCreate([
            'slug' => $field_group_definition->getSlug(),
        ], [
            'order_column'        => $field_group_definition->getOrderColumn() ?? $group_order,
            'name'                => $field_group_definition->getLabel(),
            'description'         => $field_group_definition->getDescription(),
            'application_type_id' => $application_type->id,
            'form_section_id'     => $form_section->id,
            'meta'                => $meta,
        ]);
    }

    public function seedField(
        ApplicationType $application_type,
        FormSection     $form_section,
        FieldDefinition $field_definition,
        FieldGroup|null $field_group = null,
        int|null        $field_order = null
    ): void {
        $meta = [
            'child'                => $field_definition->getChild(),
            'helper_for'           => $field_definition->getHelperForClass(),
            'helper_api_url'       => $field_definition->getHelperApiUrl(),
            'helper_target_column' => $field_definition->getHelperTargetColumn(),
        ];

        FormField::updateOrCreate([
            'slug'                => $field_definition->getSlug(),
            'form_section_id'     => $form_section->id,
            'application_type_id' => $application_type->id,
            'field_group_id'      => $field_group?->id,
        ], [
            'order_column' => $field_definition->getOrderColumn() ?? $field_order,
            'name'         => $field_definition->getLabel(),
            'language'     => $field_definition->getLanguage(),
            'placeholder'  => $field_definition->getPlaceholder(),
            'builder'      => $field_definition->getBuilder(),
            'is_required'  => $field_definition->getIsRequired(),
            'meta'         => $meta,
        ]);
    }
}
