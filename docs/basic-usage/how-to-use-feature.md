---
title: Creating An Application Type
sidebar_position: 4.1
---

## Creating An Application Type Category
Application types are grouped together using categories. Therefore, before you can create an application type, you need to create a category. 
To create a new category, you can use the following command:

```bash
php artisan paperless:category
```
The command will ask you for the slug of the category and the label. The slug is used to identify the category in the database.

## Creating An Application Type
Once you have created a category, you can create an application type. To create a new application type, you can use the following command:

```bash
php artisan paperless:type
```
The command will ask you for the following information:
1. The name of the application type
2. The category of the application type
3. The entity types that can apply for the application type

The command will create the following files:
1. A super class for the application type in the `app/Paperless/ApplicationTypes` directory
2. A class for defining the application type fields in the `app/Paperless/FieldDefinitions` directory
3. A service class for the application type in the `app/Paperless/Services` directory

### Adding Fields To The Application Type
Open up the field definition class created for the application type in the `app/Paperless/FieldDefinitions` directory.

The class will have a static `getFields` method that returns an array of field definitions.
You may use the following value objects to define the fields
1. `Javaabu\Paperless\Support\ValueObjects\SectionDefinition`
2. `Javaabu\Paperless\Support\ValueObjects\FieldGroupDefinition`
3. `Javaabu\Paperless\Support\ValueObjects\FieldDefinition`

Note that `FieldDefinition` objects can be nested within `SectionDefinition` and `FieldGroupDefinition` objects to create a nested structure of fields. and `FieldGroupDefinition` objects can be nested within `SectionDefinition` objects to create a nested structure of field groups.

After defining the fields, you can run the following commands to seed the application type with the fields and to seed the necessary permissions.
```bash
php artisan db:seed --class=ApplicationTypesSeeder
php artisan db:seed --class=ApplicationTypesPermissionsSeeder
```

Optionally, you can seed the roles again to ensure that the new permissions are added to the roles.
```bash
php artisan db:seed --class=RolesSeeder
```
