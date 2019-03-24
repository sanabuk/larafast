<?php
namespace sanabuk\larafast\tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class LarafastTest extends OrchestraTestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	public function test_test()
	{
		$this->assertTrue(true);
		$this->assertEquals(1,1);
	} 
}