<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\text;
use function Laravel\Prompts\select;
use function Laravel\Prompts\multiselect;

use Javaabu\Paperless\Domains\EntityTypes\EntityType;
use Javaabu\Paperless\Domains\ApplicationTypes\Categories\ApplicationTypeCategory;

class CreateNewApplicationTypeCommand extends Command
{
    protected $signature = 'paperless:type';

    protected $description = 'Create a new paperless application type.';

    public function handle(): void
    {
        $slug = text(
            label: 'Enter a name for the application type:',
            placeholder: 'e.g. register new user',
            required: true,
        );

        $categories = ApplicationTypeCategory::getAllApplicationCategoriesArray();
        $category = select(
            label: 'What category does this application type belong to?',
            options: array_keys($categories),
            required: true,
        );

        $entity_types_list = EntityType::all()->pluck('slug')->toArray();
        $entity_types = multiselect(
            label: 'What category does this application type belong to?',
            options: $entity_types_list,
            required: true,
        );

        $name = str($slug)->replace('_', ' ')->singular()->lower()->toString();

        (new Actions\CreateApplicationTypeMainClass())->handle($name, $category, $entity_types);
        (new Actions\CreateApplicationTypeService())->handle($name);
        (new Actions\CreateApplicationTypeFieldDefinition())->handle($name);
    }
}
