<?php


return [

    'application_model' => \App\Models\Application::class,

    'routes' => [
        'document_store' => 'api.documents.store',
    ],

    'entity_type_enum' => null,

    'language_enum' => Javaabu\Paperless\Enums\Languages::class,

    'field_types' => [
        \Javaabu\Paperless\FieldTypes\Types\TextInput::class,
        \Javaabu\Paperless\FieldTypes\Types\EmailInput::class,
    ],

    'views_layout' => 'layouts.admin',
    'views'        => '',

    'application_types' => [
    ],

    'custom-components' => [
    ],

    'public_user'       => '',
    'public_user_table' => 'public_users',

    /**
     * The model classes that are used in this application. You can extend the
     * classes and override from here
     */
    'models'            => [
        'user'             => \App\Models\User::class,
        'application'      => \Javaabu\Paperless\Domains\Applications\Application::class,
        'application_type' => \Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType::class,
        'document_type'    => \Javaabu\Paperless\Domains\DocumentTypes\DocumentType::class,
        'entity_type'      => \Javaabu\Paperless\Domains\EntityTypes\EntityType::class,
        'form_field'       => \Javaabu\Paperless\Models\FormField::class,
        'form_input'       => \Javaabu\Paperless\Models\FormInput::class,
        'form_section'     => \Javaabu\Paperless\Models\FormSection::class,
        'field_group'      => \Javaabu\Paperless\Models\FieldGroup::class,
        'service'          => \Javaabu\Paperless\Domains\Services\Service::class,
        'payment'          => '',
    ],

    'enums' => [
        'application_status' => \Javaabu\Paperless\Domains\Applications\Enums\ApplicationStatuses::class,
    ],

    'relations' => [
        'services' => false,
    ],

    'application_status' => null,
    'application_status_on_submit' => \Javaabu\Paperless\StatusActions\Statuses\PendingVerification::getMorphClass(),
    'services' => [
        'path' => app_path('/Paperless/ApplicationTypes/Services'),
        'namespace' => 'App\\Paperless\\ApplicationTypes\\Services',
    ]
];
