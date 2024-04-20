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



