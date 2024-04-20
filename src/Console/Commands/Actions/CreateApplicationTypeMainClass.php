<?php

namespace Javaabu\Paperless\Console\Commands\Actions;


class CreateApplicationTypeMainClass
{
    public function handle(string $name, ?string $category = null, ?array $entity_type_slugs = []): void
    {
        // Get the application_types.stub
        $stub_contents = file_get_contents(__DIR__ . '../../../../stubs/application_types.stub');

        // replace the placeholders with the actual values
        $stub = (new ReplacePlaceholders())->handle($stub_contents, $name);
        $stub = str_replace(
            '{{ ApplicationTypeCategory }}',
            str($category)->studly()->singular()->toString(),
            $stub
        );

        $entity_types_content = '';
        foreach ($entity_type_slugs as $slug) {
            $slug = str($slug)->studly()->singular()->replace('_', '')->toString();
            $entity_types_content .= "\n\t\t\tEntityTypes::{$slug}->value,";
        }
        $entity_types_content .= "\n\t\t";

        $stub = str_replace(
            '{{ EntityTypes }}',
            $entity_types_content,
            $stub
        );

        $file_name = str($name)->studly()->singular();

        // create app/Paperless directory if not present
        if (! is_dir(base_path('app/Paperless'))) {
            mkdir(base_path('app/Paperless'));
        }

        if (! is_dir(base_path('app/Paperless/ApplicationTypes'))) {
            mkdir(base_path('app/Paperless/ApplicationTypes'));
        }

        file_put_contents(base_path("app/Paperless/ApplicationTypes/{$file_name}.php"), $stub);
    }
}
