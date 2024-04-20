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

//        $this->call('vendor:publish', [
//            '--provider' => 'Javaabu\Paperless\PaperlessServiceProvider',
//            '--tag'      => 'paperless-config'
//        ]);
//
//        $this->call('vendor:publish', [
//            '--provider' => 'Javaabu\Paperless\PaperlessServiceProvider',
//            '--tag'      => 'paperless-migrations'
//        ]);

        $this->components->task('Attempting to install admin routes', function () {
            return (new UpdateFileAction())->handle(
                base_path('routes/admin.php'),
                "\n\n\tJavaabu\Paperless\Paperless::routes();\n",
                'Paperless::routes()',
                'Route::post(\'settings\', [SettingsController::class, \'reset\'])->name(\'settings.reset\');'
            );
        });

        $this->components->task('Attempting to install api routes', function () {
            return (new UpdateFileAction())->handle(
                base_path('routes/api.php'),
                "\n\n\t\tJavaabu\Paperless\Paperless::apiRoutes();\n",
                'Paperless::apiRoutes()',
                "Route::get('users/{id}', [UsersController::class, 'show'])->name('users.show');"
            );
        });


    }
}
