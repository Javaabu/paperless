<?php


return [

    /*
     * The Application Types that you create to be used in your
     * project.
     * */
    'application_types'           => [
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
    'routes'                      => [
        /*
         * Add your documents store route here
         * */
        'document_store' => 'api.documents.store',
        'document_show'  => 'paperless.documents.show',
    ],

    /**
     * The disk to use for storing the uploaded files. The disk should be ideally a disk that
     * is not publicly accessible over the default Laravel public storage route.
     */
    'storage_disk'                => 'paperless_uploads',

    /*
     * IMPORTANT:
     * Create an enum class for the entity types, it should implement EntityTypeEnumInterface
     * and should have the trait ActsAsEntityTypeEnum to get the default implementation
     * of the methods.
     * */
    'entity_type_enum'            => \App\Paperless\Enums\EntityTypes::class,

    'language_enum' => Javaabu\Paperless\Enums\Languages::class,


    'field_types' => [
        \Javaabu\Paperless\FieldTypes\Types\TextInput::class,
        \Javaabu\Paperless\FieldTypes\Types\EmailInput::class,
    ],

    'field_builders' => [
        \Javaabu\Paperless\Support\Builders\TextInputBuilder::class,
        \Javaabu\Paperless\Support\Builders\EmailInputBuilder::class,
    ],

    'views_layout' => 'layouts.admin',
    'views'        => '',

    'custom-components' => [
    ],

    'public_user'       => '',

    /*
     * The table that will be used to create the constraint for
     * Application Model to `Applicant` of the Application
     * */
    'public_user_table' => 'public_users',

    /**
     * The model classes that are used in this application. You can extend the
     * classes and override from here
     */
    'models'            => [
        'admin'            => \App\Models\User::class,
        'user'             => \App\Models\User::class,
        'application'      => \Javaabu\Paperless\Domains\Applications\Application::class,
        'application_type' => \Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType::class,
        'document_type'    => \Javaabu\Paperless\Domains\DocumentTypes\DocumentType::class,
        'entity_type'      => \Javaabu\Paperless\Domains\EntityTypes\EntityType::class,
        'form_field'       => \Javaabu\Paperless\Models\FormField::class,
        'form_input'       => \Javaabu\Paperless\Models\FormInput::class,
        'form_section'     => \Javaabu\Paperless\Models\FormSection::class,
        'field_group'      => \Javaabu\Paperless\Models\FieldGroup::class,
        'media'            => \Javaabu\Paperless\Support\Media\Media::class,
        'service'          => \Javaabu\Paperless\Domains\Services\Service::class,
        'payment'          => '',
    ],

    /**
     * You can change what controllers that you want Paperless to use when it
     * registers the routes. This is useful if you want to extend the
     * controllers to add extra functionality or modify
     * specific areas to your needs.
     */
    'controllers'       => [
        'applications' => \Javaabu\Paperless\Domains\Applications\ApplicationsController::class,
        'documents'    => \Javaabu\Paperless\Domains\Documents\DocumentsController::class,
    ],

    /**
     * This config section defines the policies that are used in the Paperless package.
     * Not all applications will be having the same policies, so you can define the
     * policies that you want to use in the application for Paperless models here.
     */
    'policies'          => [
        'application'      => \Javaabu\Paperless\Domains\Applications\ApplicationPolicy::class,
        'application_type' => \Javaabu\Paperless\Domains\ApplicationTypes\ApplicationTypePolicy::class,
        'document_type'    => \Javaabu\Paperless\Domains\DocumentTypes\DocumentTypePolicy::class,
        'service'          => \Javaabu\Paperless\Domains\Services\ServicePolicy::class,
        'media'            => \Javaabu\Paperless\Support\Media\MediaPolicy::class,
    ],

    /**
     * This config section defines the names of the routes that are used in
     * the Paperless package. We also define the model finder class that
     * is used to find and used for `Explicit Model Binding` so your
     * controllers know what it's looking for.
     */
    'routing'           => [
        'admin_application_param'  => 'paperless_application',
        'public_application_param' => 'paperless_public_application',
        'model_finder'             => \Javaabu\Paperless\Routing\PaperlessRouteModelFinder::class,
    ],


    'enums'                        => [
        'application_status' => \Javaabu\Paperless\Domains\Applications\Enums\ApplicationStatuses::class,
    ],

    /**
     * This config section defines what relationships on the Paperless package should be active.
     * Currently, this is only applicable for Services (AKA Automatic Payment raising).
     * This is to prevent the package from trying to load relationships that are not needed.
     */
    'relations'                    => [
        'services' => false,
    ],

    /**
     * The status class to use for the Application Model
     */
    'application_status'           => \Javaabu\Paperless\StatusActions\Statuses\ApplicationStatus::class,

    /**
     * The status action to use for when an Application is submitted
     */
    'application_status_on_submit' => \Javaabu\Paperless\StatusActions\Statuses\PendingVerification::getMorphClass(),

    /**
     * The status action to use for when an Application is created
     */
    'application_status_on_create' => \Javaabu\Paperless\StatusActions\Statuses\Draft::getMorphClass(),


    /**
     * This config section helps to define where the Service Classes for all the Applications
     * live. This is used to dynamically load the service classes for the
     * application types and help process the application data.
     *
     */
    'services'                     => [
        'path'      => app_path('/Paperless/Services'),
        'namespace' => 'App\\Paperless\\Services',
    ],
];
