<?php

namespace Javaabu\Paperless\Console\Commands\Actions;

class CreateApplicationTypeMainClass
{
    public function handle(string $name): void
    {
        // Get the application_types.stub
        $stub_contents = file_get_contents(__DIR__ . '../../../stubs/application_types.stub');

        // replace the placeholders with the actual values
        $stub = (new ReplacePlaceholders())->handle($stub_contents, $name);

        $file_name = str($name)->studly()->singular();

        // create app/Paperless directory if not present
        if (! is_dir(base_path('app/Paperless/ApplicationTypes'))) {
            mkdir(base_path('app/Paperless/ApplicationTypes'));
        }

        file_put_contents(base_path("app/Paperless/ApplicationTypes/{$file_name}.php"), $stub);
    }
}
