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
            label: 'What entity types can apply for this application type?',
            options: $entity_types_list,
            required: true,
        );

        $slug = str($slug)->slug('_')->toString();
        $name = str($slug)->replace('_', ' ')->singular()->lower()->toString();

        (new Actions\CreateApplicationTypeMainClass())->handle($name, $category, $entity_types);
        (new Actions\CreateApplicationTypeService())->handle($name);
        (new Actions\CreateApplicationTypeFieldDefinition())->handle($name);

        $this->updateConfigFile($name);

        $this->components->info("Application type created successfully.");
    }

    public function updateConfigFile(string $name): void
    {
        $file_name = str($name)->studly()->singular();

        // Get config/paperless.php file contents
        $config_contents = file_get_contents(base_path('config/paperless.php'));

        // add the new category to the application_type_categories array
        $config_contents = str_replace(
            "'application_types' => [",
            "'application_types' => [\n\t\tApp\Paperless\ApplicationTypes\\{$file_name}::class,",
            $config_contents
        );

        // write the new contents back to the config file
        file_put_contents(base_path('config/paperless.php'), $config_contents);
    }
}
