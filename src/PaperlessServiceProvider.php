<?php

namespace Javaabu\Paperless;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Javaabu\Paperless\Console\Commands\PaperlessTestCommand;
use Illuminate\Contracts\Container\BindingResolutionException;

class PaperlessServiceProvider extends ServiceProvider
{
    private $timestamp;

    public function boot(): void
    {
        $this->offerPublishing();

        $this->registerCommands();

        $this->registerModelBindings();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'paperless');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/paperless.php',
            'paperless'
        );
    }

    protected function offerPublishing(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/paperless.php' => config_path('paperless.php'),
        ], 'paperless-config');

        // Offer publishing for all the migrations in this package
        $this->daPaperlessMigrations();

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/paperless'),
        ], 'paperless-views');
    }

    protected function registerCommands(): void
    {
        // Commands accessible at all times go here.
        $this->commands([
            PaperlessTestCommand::class
        ]);

        // Stops the execution of the function so that the
        // commands below are not registered.
        if (! $this->app->runningInConsole()) {
            return;
        }

        // Commands accessible only from the terminal go here.
        $this->commands([
            //
        ]);
    }

    /**
     * Register model bindings so that the package can be extended.
     * @return void
     */
    protected function registerModelBindings(): void
    {
        // $this->app->bind(SomeContract::class, function ($app) {
        //    return $app->make($app->config['paperless.models.something']);
        // });
    }

    /**
     * @throws BindingResolutionException
     */
    protected function daPaperlessMigrations(): void
    {
        $migrations = [
            'create_entity_types_table.php',
            'create_services_table.php',
            'create_document_types_table.php',
            'add_document_type_to_media.php',
            'create_application_types_table.php',
            'create_entity_type_application_type_table.php',
            'create_application_type_service_table.php',
            'create_document_type_application_type_table.php',
            'create_form_sections_table.php',
            'create_field_groups_table.php',
            'create_form_fields_table.php',
            'create_applications_table.php',
            'create_form_inputs_table.php',
        ];

        $publishing_array = [];

        foreach ($migrations as $migration) {
            $publishing_array[__DIR__.'/../database/migrations/'.$migration] = $this->getMigrationFileName($migration);
        }

        $this->publishes($publishing_array, 'paperless-migrations');
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @throws BindingResolutionException
     */
    protected function getMigrationFileName(string $migrationFileName): string
    {
        if (!$this->timestamp) {
            $this->timestamp = now();
        }

        $this->timestamp->addSecond();

        $timestamp = $this->timestamp->format('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make([$this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR])
            ->flatMap(fn ($path) => $filesystem->glob($path.'*_'.$migrationFileName))
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }
}
