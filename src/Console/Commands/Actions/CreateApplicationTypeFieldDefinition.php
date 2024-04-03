<?php

namespace Javaabu\Paperless\Console\Commands\Actions;

class CreateApplicationTypeFieldDefinition
{
    public function handle(string $name): void
    {
        // Get the application_types.stub
        $stub_contents = file_get_contents(__DIR__ . '../../../../stubs/application_type_field_definition.stub');

        // replace the placeholders with the actual values
        $stub = (new ReplacePlaceholders())->handle($stub_contents, $name);

        $file_name = str($name)->studly()->singular()->toString() . 'FieldDefinition';

        // create app/Paperless directory if not present
        if (! is_dir(base_path('app/Paperless'))) {
            mkdir(base_path('app/Paperless'));
        }

        if (! is_dir(base_path('app/Paperless/ApplicationTypes'))) {
            mkdir(base_path('app/Paperless/ApplicationTypes'));
        }

        if (! is_dir(base_path('app/Paperless/ApplicationTypes/FieldDefinitions'))) {
            mkdir(base_path('app/Paperless/ApplicationTypes/FieldDefinitions'));
        }

        file_put_contents(base_path("app/Paperless/ApplicationTypes/FieldDefinitions/{$file_name}.php"), $stub);
    }
}
