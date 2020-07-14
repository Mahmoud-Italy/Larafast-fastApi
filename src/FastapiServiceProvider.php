<?php

namespace Larafast\Fastapi;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Support\ServiceProvider;
use Larafast\Fastapi\Console\Commands\ControllerMakeCommand;
use Larafast\Fastapi\Console\Commands\FactoryMakeCommand;
use Larafast\Fastapi\Console\Commands\Fastapi;
use Larafast\Fastapi\Console\Commands\MigrateMakeCommand;
use Larafast\Fastapi\Console\Commands\ModelMakeCommand;
use Larafast\Fastapi\Console\Commands\RequestMakeCommand;
use Larafast\Fastapi\Console\Commands\ResourceMakeCommand;

class FastapiServiceProvider extends ServiceProvider
{

    protected $commands = [
        ControllerMakeCommand::class,
        FactoryMakeCommand::class,
        Fastapi::class,
        MigrateMakeCommand::class,
        ModelMakeCommand::class,
        RequestMakeCommand::class,
        ResourceMakeCommand::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('fastApi.php'),
        ], 'fastApi');


        $this->publishes([
            __DIR__ . '/../resources/stubs' => resource_path('stubs')
        ], 'fastApi');


        $this->app->when(MigrationCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return resource_path('stubs');
            });

        $this->commands($this->commands);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'fastApi');

        // Register the main class to use with the facade
        $this->app->singleton('fastApi', function () {
            return new Fastapi;
        });
    }
}
