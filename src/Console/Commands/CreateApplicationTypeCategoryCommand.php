<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\text;

use Javaabu\Paperless\Console\Commands\Actions\ReplacePlaceholders;

class CreateApplicationTypeCategoryCommand extends Command
{
    protected $signature = 'paperless:category';

    protected $description = 'Creates a new application type category.';

    public function handle(): void
    {
        $slug = text(
            label: 'Enter a slug for the application type category:',
            placeholder: 'e.g. licensing',
            required: true,
        );

        $label = text(
            label: 'Enter a label for the application type category:',
            placeholder: 'e.g. Licensing Category',
            default: str($slug)->title()->replace('_', ' ')->toString(),
            required: true,
        );

        $stub_contents = file_get_contents(__DIR__ . '../../../stubs/application_type_category.stub');

        $stub = (new ReplacePlaceholders())->handle($stub_contents, $slug);
        $file_name = str($slug)->studly()->singular()->replace('_', '')->toString();

        if (! is_dir(base_path('app/Paperless'))) {
            mkdir(base_path('app/Paperless'));
        }

        if (! is_dir(base_path('app/Paperless/Categories'))) {
            mkdir(base_path('app/Paperless/Categories'));
        }

        file_put_contents(base_path("app/Paperless/Categories/{$file_name}.php"), $stub);

        // Get config/paperless.php file contents
        $config_contents = file_get_contents(base_path('config/paperless.php'));

        // add the new category to the application_type_categories array
        $config_contents = str_replace(
            "'application_type_categories' => [",
            "'application_type_categories' => [\n\t\tApp\Paperless\Categories\\{$file_name}::class,",
            $config_contents
        );

        // write the new contents back to the config file
        file_put_contents(base_path('config/paperless.php'), $config_contents);

        $this->info("Application type category created successfully.");
    }
}
