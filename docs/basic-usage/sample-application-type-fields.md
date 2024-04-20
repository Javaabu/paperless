---
title: Sample Application Type Fields
sidebar_position: 4.2
---

```php
class RegisterNewUserFieldDefinition
{
    public static function getFields(): array
    {
        return [
            SectionDefinition::make('user_information')
                ->label('User Information')
                ->description('Details required for creating a new user')
                ->fields([
                    FieldDefinition::make('user_name')
                        ->label('Name')
                        ->builder(TextInputBuilder::class)
                        ->isRequired(),
                    FieldDefinition::make('user_email')
                        ->label('Email')
                        ->builder(EmailInputBuilder::class)
                        ->isRequired(),
                ]),
        ];
    }
}
```
