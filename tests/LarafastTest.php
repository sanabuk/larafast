<?php
namespace sanabuk\larafast\Tests;

use sanabuk\larafast\Larafast;
use sanabuk\larafast\Tests\models\Test;

class LarafastTest extends TestCase
{
    protected $larafast;

    protected $modelA;
    protected $modelB;

    public function setUp()
    {
        parent::setUp();
        $this->larafast = new Larafast();

        $this->modelA = Test::create([
            'id'         => 1,
            'name'       => 'smooth',
            'first_name' => 'robert',
        ]);
        $this->modelB = Test::create([
            'id'         => 2,
            'name'       => 'doe',
            'first_name' => 'john',
        ]);
    }

    public function test_function_is_sort()
    {
        $this->assertEquals($this->invokeMethod($this->larafast, 'isSort', ['sort']), true);
        $this->assertEquals($this->invokeMethod($this->larafast, 'isSort', ['sorti']), false);
        $this->assertEquals(1, 1);
    }

    public function test_add_sort()
    {
        $query = Test::query();
        $model = $this->invokeMethod($this->larafast, 'addSort', [$query, 'name', 'ASC'])->get();
        $this->assertEquals($model[0]->id, $this->modelB->id);

        $query = Test::query();
        $model = $this->invokeMethod($this->larafast, 'addSort', [$query, 'name', 'DESC'])->get();
        $this->assertEquals($model[0]->id, $this->modelA->id);
    }

    public function test_function_is_where()
    {
        $this->assertEquals($this->invokeMethod($this->larafast, 'isWhere', [0]), false);
        $this->assertEquals($this->invokeMethod($this->larafast, 'isWhere', ['equals']), true);
    }

    public function test_add_where()
    {
        $query = Test::query();
        $model = $this->invokeMethod($this->larafast, 'addWhere', [$query, 'name', 'smooth', '='])->get();
        $this->assertEquals($model[0]->id, $this->modelA->id);

        $query = Test::query();
        $model = $this->invokeMethod($this->larafast, 'addWhere', [$query, 'name', 'o', 'like'])->get();
        $this->assertEquals($model[0]->id, $this->modelA->id);
        $this->assertEquals($model[1]->id, $this->modelB->id);
    }

    public function test_function_is_select()
    {
        $this->assertEquals($this->invokeMethod($this->larafast, 'isSelect', [0]), true);
        $this->assertEquals($this->invokeMethod($this->larafast, 'isSelect', ['relation']), false);
    }

    public function test_function_add_select()
    {
        $query  = Test::query();
        $models = $this->invokeMethod($this->larafast, 'addSelect', [$query, 'name']);
        $this->assertEquals($models->toArray(),
            [
                0 => ['name' => 'smooth'],
                1 => ['name' => 'doe'],
            ]
        );
    }
}
