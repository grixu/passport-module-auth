<?php

namespace Grixu\PassportModuleAuth;

use Grixu\PassportModuleAuth\Console\ModuleAuthCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Class PassportModuleAuthServiceProvider
 * @package Grixu\PassportModuleAuth
 */
class PassportModuleAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../config/config.php' => config_path('passport-module-auth.php'),
                ],
                'config'
            );

            // Registering package commands.
            $this->commands(
                [
                    ModuleAuthCommand::class,
                ]
            );
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'passport-module-auth');
    }
}
