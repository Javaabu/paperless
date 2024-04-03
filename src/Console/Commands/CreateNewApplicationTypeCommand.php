<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;

class CreateNewApplicationTypeCommand extends Command
{
    protected $signature = 'paperless:application-type {name : The name of the application type in snake case}';

    protected $description = 'Create a new application type.';

    public function handle(): void
    {
        $name = $this->argument('name');

        // Get the application_types.stub
        $stub = file_get_contents(__DIR__ . '../../../stubs/application_types.stub');

        // replace the placeholders with the actual values
        $stub = str_replace(
            [
                '{{ application_type_slug }}',
            ],
            [
                $name,
            ],
            $stub
        );

        $file_name = str($name)->studly()->singular();

        // create app/Paperless directory if not present
        if (! is_dir(base_path('app/Paperless/ApplicationTypes'))) {
            mkdir(base_path('app/Paperless/ApplicationTypes'));
        }

        file_put_contents(base_path("app/Paperless/ApplicationTypes/{$file_name}.php"), $stub);
    }
}
