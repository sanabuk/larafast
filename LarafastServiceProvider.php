<?php
namespace sanabuk\larafast;

use Illuminate\Support\ServiceProvider;
use sanabuk\larafast\ParentheseParser;

class LarafastServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/larafast.php' => config_path('larafast.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/../src/routes.php');
    }

    public function register()
    {
        $this->app->bind('ParentheseParser', function ($app) {
            $driver = new ParentheseParser();
        });
    }
}
