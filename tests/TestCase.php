<?php
namespace sanabuk\larafast\Tests;

use sanabuk\larafast\LarafastServiceProvider;
use sanabuk\larafast\LarafastFacade;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return sanabuk\larafast\LarafastServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [LarafastServiceProvider::class];
    }
    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Larafast' => LarafastFacade::class,
        ];
    }
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}