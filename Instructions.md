# Adding Paperless To Admin Panel

Add the following to the sidebar menu

```php
MenuItem::make(__('Paperless'))
    ->icon('zmdi-file-text')
    ->children([
        MenuItem::make(__('Applications'))
                ->url(route('admin.applications.index'))
                ->active(request()->routeIs('admin.applications.*')),
        MenuItem::make(__('Application Types'))
                ->url(route('admin.application-types.index'))
                ->active(request()->routeIs('admin.application-types.*')),
        MenuItem::make(__('Document Types'))
            ->url(route('admin.document-types.index'))
            ->active(request()->routeIs('admin.document-types.*')),
        MenuItem::make(__('All Services'))
            ->url(route('admin.services.index'))
            ->active(request()->routeIs('admin.services.*')),
    ]),
```

In routes file

```php
Javaabu\Paperless\Paperless::routes();
```

# Creating New Application Type

First create an enum to define entity types and add it to the config file, implement EntityTypeEnumInterface

```php
    'entity_type_enum' => App\Paperless\Enums\EntityTypes::class,
```

To create a sample application types

```bash
php artisan paperless:paperless:sample-application-type
```

This will generate the following files

```bash
app/Paperless/ApplicationTypes/RegisterNewUser.php
app/Paperless/ApplicationTypes/Services/RegisterNewUserService.php
app/Paperless/ApplicationTypes/FieldDefinitions/RegisterNewUserFieldDefinitions.php
```

Create a new application type category using the following command

```bash
php artisan paperless:paperless:application-type-category user_category
```

Add the created category to the RegisterNewUser.php file

```php
protected $category = 'user_category';
```

Update the RegisterNewUserFields.php with the fields you need. See SampleApplicationTypeFields.md for reference.

Seed the application type.

```bash
php artisan db:seed --class=Javaabu\Paperless\Database\Seeders\ApplicationTypesSeeder
```
