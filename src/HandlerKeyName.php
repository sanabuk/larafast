<?php
namespace sanabuk\larafast;

class HandlerKeyName
{
	protected $relation;

	public function __construct($relation){
		$this->relation = $relation;
		dd(substr($relation, strrpos($relation, '\\') + 1));
	}
}