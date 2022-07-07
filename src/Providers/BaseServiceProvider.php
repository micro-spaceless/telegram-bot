<?php

namespace MicroSpaceless\TelegramBot\Providers;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Abstract type to bind Sentry as in the Service Container.
     *
     * @var string
     */
    public static $abstract = 'telegram';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Append the default settings
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            static::$abstract,
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path(static::$abstract . '.php'),
        ], 'config');

        // Append the default settings
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            static::$abstract,
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [static::$abstract];
    }
}
