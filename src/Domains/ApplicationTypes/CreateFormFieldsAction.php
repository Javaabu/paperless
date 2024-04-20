<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Models\FieldGroup;
use Javaabu\Paperless\Models\FormSection;

class CreateFormFieldsAction
{
    public function handle(ApplicationType $application_type, array $form_field_data): void
    {
        $section_order = 1;
        foreach ($form_field_data as $form_section_data) {
            $form_section = FormSection::updateOrCreate([
                'slug' => $form_section_data['slug'],
            ], [
                'order_column'        => $section_order,
                'name'                => $form_section_data['name'],
                'description'         => $form_section_data['description'],
                'application_type_id' => $application_type->id,
                'is_admin_section'    => $form_section_data['is_admin_section'] ?? false,
            ]);

            $field_groups = data_get($form_section_data, 'groups', []);
            $group_order = 1;

            foreach ($field_groups as $field_group_data) {
                $has_field_group = data_get($field_group_data, 'name', null);
                $field_group = null;

                if (! empty($has_field_group)) {
                    $field_group = FieldGroup::updateOrCreate([
                        'slug' => $field_group_data['slug'],
                    ], [
                        'order_column'        => $group_order,
                        'name'                => $field_group_data['name'],
                        'description'         => $field_group_data['description'],
                        'application_type_id' => $application_type->id,
                        'form_section_id'     => $form_section->id,
                    ]);
                }

                $fields = data_get($field_group_data, 'fields', []);
                $field_order = 1;

                foreach ($fields as $field) {
                    $field_type = $field['type'] ? (new $field['type']())->getSlug() : null;
                    FormField::updateOrCreate([
                        'slug'                => $field['slug'],
                        'form_section_id'     => $form_section->id,
                        'application_type_id' => $application_type->id,
                        'field_group_id'      => $field_group?->id,
                    ], [
                        'order_column'                => $field['order_column'] ?? $field_order,
                        'name'                        => $field['name'],
                        'language'                    => $field['language'] ?? 'en',
                        'placeholder'                 => data_get($field, 'placeholder', null),
                        'builder'                        => $field_type,
                        'is_required'                 => $field['required'],
                    ]);

                    $field_order++;
                }

                $group_order++;
            }

            $section_order++;
        }
    }
}
