<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;

class CreateNewApplicationTypeCommand extends Command
{
    protected $signature = 'make:paperless-application-type {name : The name of the application type in snake case}';

    protected $description = 'Create a new paperless application type.';

    public function handle(): void
    {
        $name = $this->argument('name');

        (new Actions\CreateApplicationTypeMainClass())->handle($name);
        (new Actions\CreateApplicationTypeService())->handle($name);
        (new Actions\CreateApplicationTypeFieldDefinition())->handle($name);
    }
}
