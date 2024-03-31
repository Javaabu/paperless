<?php

namespace Javaabu\Paperless;

use Illuminate\Support\ServiceProvider;

class PaperlessServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // declare publishes
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('paperless.php'),
            ], 'paperless-config');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'paperless');
    }

    public function register(): void
    {
        // merge package config with user defined config
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'paperless');
    }
}
