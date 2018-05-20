<?php

namespace DigitalUnityCa\Translatable;

use DigitalUnityCa\Translatable\App\Console\Commands\MakeTranslatable;
use Illuminate\Support\ServiceProvider;

class TranslatableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
        $this->loadConsoleCommands();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('translatable', function(){
            return new Translatable();
        });
    }

    /**
     * Commands
     */
    public function loadConsoleCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeTranslatable::class,
            ]);
        }
    }

    /**
     * Publish configs
     */
    public function publishConfig()
    {
        // Override configs
        $this->publishes([
            __DIR__ . '/config/backpack/translatable.php' => config_path('backpack/translatable.php'),
        ],'backpack-translatable-config');

    }

}
