<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;

class CreateSampleApplicationTypeCommand extends Command
{
    protected $signature = 'paperless:sample-application-type';

    protected $description = 'Create a sample application type.';

    public function handle(): void
    {
        // call another artisan command
        $this->call('paperless:application-type', [
            'name' => 'register_new_user',
        ]);
    }
}
