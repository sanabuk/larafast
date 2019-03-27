<?php
namespace sanabuk\larafast\Tests\models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
	public $table = 'tests';
	public $fillable = [
		'id',
		'name'
	];
}