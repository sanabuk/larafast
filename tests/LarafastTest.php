<?php
namespace sanabuk\larafast\Tests;

use sanabuk\larafast\Larafast;

class LarafastTest extends TestCase
{
	protected $larafast;

	public function setUp()
	{
		parent::setUp();
		$this->larafast = new Larafast();
	}

	public function test_function_is_sort()
	{
		$this->assertEquals($this->invokeMethod($this->larafast,'isSort',['sort']), true);
		$this->assertEquals($this->invokeMethod($this->larafast,'isSort',['sorti']), false);
		$this->assertEquals(1,1);
	}
}