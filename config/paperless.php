<?php


return [

    'application_model' => \App\Models\Application::class,

    'field_types' => [
        \Javaabu\Paperless\FieldTypes\Types\TextInput::class,
    ],

    'views_layout' => 'layouts.admin',
    'views'        => '',

    'application_types' => [
        \App\Application\ApplicationTypes\CreateAcademy::class,
        \App\Application\ApplicationTypes\CreateAgency::class,
        \App\Application\ApplicationTypes\AddInstructor::class,
        \App\Application\ApplicationTypes\RemoveInstructor::class,
        \App\Application\ApplicationTypes\AddBatchCertificates::class,
        \App\Application\ApplicationTypes\AddExistingCertificate::class,
        \App\Application\ApplicationTypes\AddExistingLicense::class,
        \App\Application\ApplicationTypes\NewLicense::class,
        \App\Application\ApplicationTypes\AddCategory::class,
        \App\Application\ApplicationTypes\TemporaryLicense::class,
        \App\Application\ApplicationTypes\TonnageEndorsement::class,
        \App\Application\ApplicationTypes\RenewLicense::class,
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
        'application'      => \Javaabu\Paperless\Models\Application::class,
        'application_type' => \Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType::class,
        'document_type'    => \Javaabu\Paperless\Domains\DocumentTypes\DocumentType::class,
        'entity_type'      => \Javaabu\Paperless\Models\EntityType::class,
        'form_field'       => \Javaabu\Paperless\Models\FormField::class,
        'form_input'       => \Javaabu\Paperless\Models\FormInput::class,
        'form_section'     => \Javaabu\Paperless\Models\FormSection::class,
        'field_group'      => \Javaabu\Paperless\Models\FieldGroup::class,
        'service'          => \Javaabu\Paperless\Domains\Services\Service::class,
    ],
];
