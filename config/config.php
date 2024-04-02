<?php

return [
    'custom-components'  => [
    ],

    'views' => '',

    'application_model' => \Javaabu\Paperless\Models\Application::class,

    'public_user' => \App\Models\PublicUser::class,

    /**
     * The model classes that are used in this application. You can extend the
     * classes and override from here
     */
    'models' => [
        'application' => \Javaabu\Paperless\Models\Application::class,
        'application_type' => \Javaabu\Paperless\Models\ApplicationType::class,
        'document_type' => \Javaabu\Paperless\Models\DocumentType::class,
        'entity_type' => \Javaabu\Paperless\Models\EntityType::class,
        'form_field' => \Javaabu\Paperless\Models\FormField::class,
        'form_input' => \Javaabu\Paperless\Models\FormInput::class,
        'form_section' => \Javaabu\Paperless\Models\FormSection::class,
        'field_group' => \Javaabu\Paperless\Models\FieldGroup::class,
        'service' => \Javaabu\Paperless\Models\Service::class,
    ],
];
