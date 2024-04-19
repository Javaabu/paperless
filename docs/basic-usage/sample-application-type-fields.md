---
title: Sample Application Type Fields
sidebar_position: 4.2
---

```php
[
    [
        'name'        => 'User Information',
        'slug'        => 'user_information',
        'description' => 'Details required for creating a new user',
        'groups'      => [
            [
                'fields' => [
                    [
                        'name'                        => 'Name',
                        'slug'                        => 'user_name',
                        'type'                        => \Javaabu\Paperless\FieldTypes\Types\TextInput::class
                        'required'                    => true,
                    ],
                    [
                        'name'                        => 'Email',
                        'slug'                        => 'user_email',
                        'type'                        => \Javaabu\Paperless\FieldTypes\Types\EmailInput::class
                        'required'                    => true,
                    ],
                ],
            ],
        ],
        
    ],
    [
        'name'        => 'User Role',
        'slug'        => 'user_role',
        'description' => 'The role of the user',
        'is_admin_section' => true,
        'groups'      => [
            [
                'fields' => [
                    [
                        'name' => 'Role',
                        'slug' => 'user_role',
                        'type' => \Javaabu\Paperless\FieldTypes\Types\EmailInput::class
                        'required' => true,
                    ]
                ]
            ]
        ],
    ]
];
```
