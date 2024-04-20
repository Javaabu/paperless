<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;
use Javaabu\Paperless\Console\Commands\Actions\ReplacePlaceholders;

class CreateApplicationTypeCategoryCommand extends Command
{
    protected $signature = 'paperless:category {name}';

    protected $description = 'Creates a new application type category.';

    public function handle(): void
    {
        $name = $this->argument('name');
        $label = str($name)->title()->replace('_', ' ')->toString();
        $this->info("Creating application type category: $label");

        $stub_contents = file_get_contents(__DIR__ . '../../../stubs/application_type_category.stub');

        $stub = (new ReplacePlaceholders())->handle($stub_contents, $name);
        $file_name = str($name)->studly()->singular()->toString();

        if (! is_dir(base_path('app/Paperless'))) {
            mkdir(base_path('app/Paperless'));
        }

        if (! is_dir(base_path('app/Paperless/ApplicationTypeCategories'))) {
            mkdir(base_path('app/Paperless/ApplicationTypeCategories'));
        }

        file_put_contents(base_path("app/Paperless/ApplicationTypeCategories/{$file_name}.php"), $stub);

        $this->info("Application type category created successfully.");
    }
}
