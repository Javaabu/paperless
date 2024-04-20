<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;

use function Laravel\Prompts\text;

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

        $name = str($slug)->replace('_', ' ')->singular()->lower()->toString();

        (new Actions\CreateApplicationTypeMainClass())->handle($name);
        (new Actions\CreateApplicationTypeService())->handle($name);
        (new Actions\CreateApplicationTypeFieldDefinition())->handle($name);
    }
}
