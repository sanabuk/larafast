<?php
namespace sanabuk\larafast;

use Illuminate\Support\ServiceProvider;
use sanabuk\larafast\ParentheseParser;

class LarafastServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind('ParentheseParser', function ($app) {
            $driver = new ParentheseParser();
        });
    }
}
