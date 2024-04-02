<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class PaperlessTestCommand extends Command
{
    protected $signature = 'paperless:test-command';

    protected $description = 'Test command for Paperless Package.';

    public function handle(): int
    {
        $this->info('This command ran successfully.');

        return SymfonyCommand::SUCCESS;
    }
}
