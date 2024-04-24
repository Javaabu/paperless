<?php

namespace Javaabu\Paperless;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Javaabu\StatusEvents\Models\StatusEvent;
use Illuminate\Database\Eloquent\Relations\Relation;
use Javaabu\Paperless\Domains\Services\ServicePolicy;
use Javaabu\Paperless\Console\Commands\PaperlessTestCommand;
use Javaabu\Paperless\Domains\Applications\ApplicationPolicy;
use Illuminate\Contracts\Container\BindingResolutionException;
use Javaabu\Paperless\Console\Commands\PaperlessInstallCommand;
use Javaabu\Paperless\Domains\DocumentTypes\DocumentTypePolicy;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationTypePolicy;
use Javaabu\Paperless\Console\Commands\CreateNewApplicationTypeCommand;
use Javaabu\Paperless\Console\Commands\CreateApplicationTypeCategoryCommand;

class PaperlessServiceProvider extends ServiceProvider
{
    private $timestamp;

    public function boot(): void
    {
        $this->offerPublishing();

        $this->registerCommands();

        $this->registerModelBindings();

        $this->registerPolicies();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'paperless');

        Relation::enforceMorphMap([
            'application'      => config('paperless.models.application'),
            'application_type' => config('paperless.models.application_type'),
            'document_type'    => config('paperless.models.document_type'),
            'entity_type'      => config('paperless.models.entity_type'),
            'service'          => config('paperless.models.service'),
            'status_event'     => StatusEvent::class,
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/paperless.php',
            'paperless'
        );
    }

    /**
     * @throws BindingResolutionException
     */
    protected function offerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/paperless.php' => config_path('paperless.php'),
        ], 'paperless-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/create_paperless_migrations_table.php' => $this->getMigrationFileName('create_paperless_migrations_table.php'),
        ], 'paperless-migrations');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/paperless'),
        ], 'paperless-views');

        $this->publishes([
            __DIR__ . '/stubs/entity_types_seeder.stub'                  => database_path('seeders/EntityTypesSeeder.php'),
            __DIR__ . '/stubs/application_types_seeder.stub'             => database_path('seeders/ApplicationTypesSeeder.php'),
            __DIR__ . '/stubs/application_types_permissions_seeder.stub' => database_path('seeders/ApplicationTypesPermissionsSeeder.php'),
            __DIR__ . '/stubs/paperless_model_permissions.stub'          => database_path('seeders/PaperlessModelPermissionsSeeder.php'),
        ], 'paperless-seeders');

        $this->publishes([
            __DIR__ . '/stubs/entity_types.stub' => app_path('Paperless/Enums/EntityTypes.php'),
        ], 'paperless-enums');
    }

    protected function registerCommands(): void
    {
        // Commands accessible at all times go here.
        $this->commands([
            PaperlessTestCommand::class,
            PaperlessInstallCommand::class,
            CreateNewApplicationTypeCommand::class,
            CreateApplicationTypeCategoryCommand::class,
        ]);

        // Stops the execution of the function so that the
        // commands below are not registered.
        if (! $this->app->runningInConsole()) {
            return;
        }
    }

    /**
     * Register model bindings so that the package can be extended.
     *
     * @return void
     */
    protected function registerModelBindings(): void
    {
        // $this->app->bind(SomeContract::class, function ($app) {
        //    return $app->make($app->config['paperless.models.something']);
        // });
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @throws BindingResolutionException
     */
    protected function getMigrationFileName(string $migrationFileName): string
    {
        if (! $this->timestamp) {
            $this->timestamp = now();
        }

        $this->timestamp->addSecond();

        $timestamp = $this->timestamp->format('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR])
                         ->flatMap(fn ($path) => $filesystem->glob($path . '*_' . $migrationFileName))
                         ->push($this->app->databasePath() . "/migrations/{$timestamp}_{$migrationFileName}")
                         ->first();
    }

    protected function registerPolicies(): void
    {
        $policies = [
            config('paperless.models.application')      => ApplicationPolicy::class,
            config('paperless.models.application_type') => ApplicationTypePolicy::class,
            config('paperless.models.service')          => ServicePolicy::class,
            config('paperless.models.document_type')    => DocumentTypePolicy::class,
        ];

        foreach ($policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }
}
