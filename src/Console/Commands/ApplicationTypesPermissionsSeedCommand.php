<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class ApplicationTypesPermissionsSeedCommand extends Command
{
    protected $signature = 'paperless:permissions';

    protected $description = 'Call the ApplicationTypesPermissionsSeeder to seed the permissions for the application types.';

    public function handle(): int
    {
        if (! class_exists(\Spatie\Permission\Models\Permission::class)) {
            $this->error('The Spatie Permission package is not installed. Please install it to use this command.');

            return SymfonyCommand::FAILURE;
        }

        $this->call('db:seed', ['--class' => 'Javaabu\Paperless\Database\Seeders\ApplicationTypesPermissionsSeeder']);

        return SymfonyCommand::SUCCESS;
    }
}
