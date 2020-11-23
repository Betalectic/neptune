<?php

namespace Betalectic\Neptune;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class NeptuneServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->setUpConfig();
    }

    protected function setUpConfig()
    {
        $source = dirname(__DIR__) . '/config/neptune.php';

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$source => config_path('neptune.php')], 'config');
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('neptune');
        }

        $this->mergeConfigFrom($source, 'neptune');
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}
