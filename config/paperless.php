<?php


return [

    /*
     * Add the application type classes here
     * */
    'application_types' => [
    ],

    /*
     * Add the application type category classes here, these
     * will be used to categorize the application types
     * */
    'application_type_categories' => [
    ],

    /*
     * Paperless requires some other routes defined in your project,
     * provide any required routes here
     * */
    'routes' => [
        /*
         * Add your documents store route here
         * */
        'document_store' => 'api.documents.store',
    ],

    /*
     * IMPORTANT:
     * Create an enum class for the entity types, it should implement EntityTypeEnumInterface
     * and should have the trait ActsAsEntityTypeEnum to get the default implementation
     * of the methods.
     * */
    'entity_type_enum' => null,

    'language_enum' => Javaabu\Paperless\Enums\Languages::class,


    'field_types' => [
        \Javaabu\Paperless\FieldTypes\Types\TextInput::class,
        \Javaabu\Paperless\FieldTypes\Types\EmailInput::class,
    ],

    'views_layout' => 'layouts.admin',
    'views'        => '',

    'custom-components' => [
    ],

    'public_user'       => '',

    /*
     * This table will be used to store the public user id in the application,
     * */
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

    'application_status' => \Javaabu\Paperless\StatusActions\Statuses\ApplicationStatus::class,
    'application_status_on_submit' => \Javaabu\Paperless\StatusActions\Statuses\PendingVerification::getMorphClass(),
    'application_status_on_create' => \Javaabu\Paperless\StatusActions\Statuses\Draft::getMorphClass(),
    'services' => [
        'path' => app_path('/Paperless/ApplicationTypes/Services'),
        'namespace' => 'App\\Paperless\\ApplicationTypes\\Services',
    ]
];
