<?php

namespace Javaabu\Paperless;

use Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Javaabu\Paperless\Providers\EventServiceProvider;
use Javaabu\Paperless\Routing\PaperlessRouteModelFinder;
use Javaabu\Paperless\Console\Commands\PaperlessTestCommand;
use Illuminate\Contracts\Container\BindingResolutionException;
use Javaabu\Paperless\Support\StatusEvents\Models\StatusEvent;
use Javaabu\Paperless\Console\Commands\PaperlessInstallCommand;
use Javaabu\Paperless\Exceptions\PolicyModelClassesNotConfigured;
use Javaabu\Paperless\Middleware\OverridePaperlessComponentViews;
use Javaabu\Paperless\Console\Commands\CreateNewApplicationTypeCommand;
use Javaabu\Paperless\Console\Commands\CreateApplicationTypeCategoryCommand;

class PaperlessServiceProvider extends ServiceProvider
{
    private $timestamp;

    /**
     * @throws BindingResolutionException
     * @throws PolicyModelClassesNotConfigured
     */
    public function boot(): void
    {
        $this->offerPublishing();

        $this->registerCommands();

        $this->registerModelBindings();

        $this->registerPolicies();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'paperless');

        $this->app->register(EventServiceProvider::class);

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

        $this->registerMiddlewareAliases();
    }

    public function registerMiddlewareAliases(): void
    {
        app('router')->aliasMiddleware('paperless-views', OverridePaperlessComponentViews::class);
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

        // Register console only commands below
        //
    }

    /**
     * Register model bindings so that the package can be extended.
     *
     * @return void
     */
    protected function registerModelBindings(): void
    {
        $paperless_admin_application_param = config('paperless.routing.admin_application_param');
        Route::bind($paperless_admin_application_param, function ($value) {
            /* @var PaperlessRouteModelFinder $finder */
            $finder = app(config('paperless.routing.model_finder'));

            return $finder->forAdmin($value);
        });

        $paperless_public_application_param = config('paperless.routing.public_application_param');
        Route::bind($paperless_public_application_param, function ($value) {
            $finder = app(config('paperless.routing.model_finder'));

            return $finder->forPublic($value);
        });
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

    /**
     * @throws PolicyModelClassesNotConfigured
     */
    protected function registerPolicies(): void
    {
        $this->ensurePolicyModelClassesAreConfigured();

        $policies = $this->getPolicies();

        foreach ($policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * @throws PolicyModelClassesNotConfigured
     */
    private function ensurePolicyModelClassesAreConfigured(): void
    {
        $policy_keys = array_keys(config('paperless.policies'));

        $missing_model_policies = [];

        foreach ($policy_keys as $policy_key) {
            if (! config("paperless.models.{$policy_key}")) {
                $missing_model_policies[] = $policy_key;
            }
        }

        if (count($missing_model_policies) > 0) {
            throw new PolicyModelClassesNotConfigured();
        }
    }

    private function getPolicies(): array
    {
        $policies = [];

        foreach (config('paperless.policies') as $model_name => $policy) {
            $policies[config("paperless.models.{$model_name}")] = $policy;
        }

        return $policies;
    }
}
