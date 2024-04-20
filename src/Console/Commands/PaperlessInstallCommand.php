<?php

namespace Javaabu\Paperless\Console\Commands;

use Illuminate\Console\Command;
use Javaabu\Paperless\Console\Commands\Actions\UpdateFileAction;

class PaperlessInstallCommand extends Command
{
    protected $signature = 'paperless:install';

    protected $description = 'This command will install the Paperless package and setup the necessary configurations.';

    public function handle(): void
    {
        $this->components->info('Paperless package installation started...');

        $this->components->task('Attempting to install routes', function () {
            (new UpdateFileAction())->handle(
                base_path('routes/admin.php'),
                "\n\n\tJavaabu\Paperless\Paperless::routes();\n",
                'Paperless::routes()',
                'Route::post(\'settings\', [SettingsController::class, \'reset\'])->name(\'settings.reset\');'
            );
        });
    }
}
