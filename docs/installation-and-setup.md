---
title: Installation & Setup
sidebar_position: 3
---

## Introduction

You can install the package via composer:

```bash
composer require javaabu/paperless
```

# Publishing the config file

Publishing the config file is optional:

```bash
php artisan vendor:publish --provider="Javaabu\Paperless\PaperlessServiceProvider" --tag="paperless-config"
```

Next, you need to publish the migrations:

```bash
php artisan vendor:publish --provider="Javaabu\Paperless\PaperlessServiceProvider" --tag="paperless-migrations"
```

# Updating the config file

After publishing the config file, you must update the following fields.
```php

    /*
     * IMPORTANT:
     * Create an enum class for the entity types, it should implement EntityTypeEnumInterface
     * and should have the trait ActsAsEntityTypeEnum to get the default implementation
     * of the methods.
     * */
    'entity_type_enum' => null,


```

