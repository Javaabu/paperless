# Adding Paperless To Admin Panel

Add the following to the sidebar menu
```php
MenuItem::make(__('Paperless'))
    ->icon('zmdi-file-text')
    ->children([
        MenuItem::make(__('Application Types'))
                ->url(route('admin.application-types.index'))
                ->active(request()->routeIs('admin.application-types.*')),
        MenuItem::make(__('All Services'))
            ->url(route('admin.services.index'))
            ->icon('zmdi-file-text')
            ->active(request()->routeIs('admin.services.*')),
    ]),
```

In routes file
```php
Javaabu\Paperless\Paperless::routes();
```

# Creating New Application Type
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
Update the RegisterNewUserFields.php with the fields you need. See SampleApplicationTypeFields.md for reference.

Seed the application type.
```bash
php artisan db:seed --class=Javaabu\\Paperless\\Database\\Seeders\\ApplicationTypeSeeder
```
