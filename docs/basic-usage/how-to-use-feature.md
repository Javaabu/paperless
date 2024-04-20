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

