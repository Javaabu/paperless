---
title: Installation & Setup
sidebar_position: 3
---

## Introduction

You can install the package via composer:

```bash
composer require javaabu/paperless
```

## Package Installation
To install the package, you can run the following command:

```bash
php artisan paperless:install
```

This command will:
1. Publish the package configuration file.
2. Publish the package migrations.
3. Install seeders.
4. Create entity types enum in `app/Paperless/Enums` directory. 
5. Add routes to your `routes/admin.php` file.
6. Add api routes to your `routes/api.php` file. 
7. Add menu items to your `app/Menus/AdminSidebar.php` file.

You should implement `Javaabu\Paperless\Interfaces\Applicant` interface on any model that acts as an applicant, such as `User` or `PublicUser` model.

You should also implement `Javaabu\StatusEvents\Interfaces\TrackingSubject` interface on the `User` model.

To enable document uploading, you must have an api route that accepts file uploads. After setting up the route, you can update the `paperless.php` configuration file to point to the route.

```php
    'routes' => [
        'document_store' => 'api.documents.store'
    ],
````

**Notice**
You should add your own `Media` model that extends `SpatieMedia` model to your project. This is because the package assumes you have a relation with documentType on the `Media` model. 

```php
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(config('paperless.models.document_type'));
    }
```
Additionally, don't forget to point to your `Media` model in the `media-library.php` configuration file.

```php
    'media_model' => \App\Models\Media::class,
```

You may store your Media Model wherever you may please. Just make sure to update the configuration file to point to the correct model.


## Seeding Data
First you need to seed the entity types. You may update the EntityTypes enum created during the installation process to add/update the entity types you want to seed. Then you can run the following command to seed the entity types.
```bash
php artisan db:seed --class=EntityTypesSeeder
```

Then you need to seed the model permissions
```bash
php artisan db:seed --class=PaperlessModelPermissionsSeeder
```

**Optional**: You will need to give your user the seeded permissions to access the package models. Normally you would need to run the following command to seed the permissions for the user.
```bash
php artisan db:seed --class=RolesSeeder
```



