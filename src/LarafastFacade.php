<?php
namespace sanabuk\larafast;

use Illuminate\Support\Facades\Facade;

class LarafastFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Larafast';
    }
}