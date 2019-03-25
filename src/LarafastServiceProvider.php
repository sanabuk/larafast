<?php
namespace sanabuk\larafast;

use Illuminate\Support\ServiceProvider;

class LarafastServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/larafast.php' => config_path('larafast.php'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }

    public function register()
    {
        $this->app->singleton(Larafast::class, function () {
            return new Larafast();
        });
        $this->app->alias(Larafast::class, 'Larafast');
        $this->app->bind('ParentheseParser', function ($app) {
            return new ParentheseParser();
        });
        $this->app->bind('LarafastController', function ($app) {
            return new LarafastController();
        });
        $this->app->bind('Larafast', function ($app) {
            return new Larafast();
        });
    }
}
