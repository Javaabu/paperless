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

        $this->publishConfigFile();
        $this->publishMigrationFile();
        $this->installSeeders();

        $this->installAdminRoutes();
        $this->installApiRoutes();
        $this->addMenuItems();
    }

    public function publishConfigFile(): void
    {
        $this->call('vendor:publish', [
            '--provider' => 'Javaabu\Paperless\PaperlessServiceProvider',
            '--tag'      => 'paperless-config'
        ]);
    }

    public function publishMigrationFile(): void
    {
        $this->call('vendor:publish', [
            '--provider' => 'Javaabu\Paperless\PaperlessServiceProvider',
            '--tag'      => 'paperless-migrations'
        ]);
    }

    private function installAdminRoutes(): void
    {
        $this->components->task('Attempting to install admin routes', function () {
            return (new UpdateFileAction())->handle(
                base_path('routes/admin.php'),
                "\n\n\tJavaabu\Paperless\Paperless::routes();\n",
                'Paperless::routes()',
                'Route::post(\'settings\', [SettingsController::class, \'reset\'])->name(\'settings.reset\');'
            );
        });
    }

    private function installApiRoutes(): void
    {
        $this->components->task('Attempting to install api routes', function () {
            return (new UpdateFileAction())->handle(
                base_path('routes/api.php'),
                "\n\n\t\tJavaabu\Paperless\Paperless::apiRoutes();\n",
                'Paperless::apiRoutes()',
                "Route::get('users/{id}', [UsersController::class, 'show'])->name('users.show');"
            );
        });
    }

    private function addMenuItems(): void
    {
        $this->components->task('Adding sidebar menu items', function () {
            $content_to_insert = "\n
            MenuItem::make(__('Paperless'))
                ->icon('zmdi-file-text')
                ->children([
                    MenuItem::make(__('Applications'))
                            ->url(route('admin.applications.index'))
                            ->active(request()->routeIs('admin.applications.*')),
                    MenuItem::make(__('Application Types'))
                            ->url(route('admin.application-types.index'))
                            ->active(request()->routeIs('admin.application-types.*')),
                    MenuItem::make(__('Document Types'))
                        ->url(route('admin.document-types.index'))
                        ->active(request()->routeIs('admin.document-types.*')),
                    MenuItem::make(__('All Services'))
                        ->url(route('admin.services.index'))
                        ->active(request()->routeIs('admin.services.*')),
                ]),";

            return (new UpdateFileAction())->handle(
                base_path('app/Menus/AdminSidebar.php'),
                $content_to_insert,
                "MenuItem::make(__('Paperless'))",
                "->route('admin.home'),"
            );
        });
    }

    private function installSeeders(): void
    {
        // copy seeders from the package to the project
        $this->call('vendor:publish', [
            '--provider' => 'Javaabu\Paperless\PaperlessServiceProvider',
            '--tag'      => 'paperless-seeders'
        ]);
    }
}
