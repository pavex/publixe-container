<?php

require_once __DIR__ . '/../src/DI/IContainer.php';
require_once __DIR__ . '/../src/DI/Container.php';
require_once __DIR__ . '/../src/Container.php';

//
class SomeClass
{
	private $arg1;
	private $arg2;

	public function __construct($arg1)
	{
		$this -> arg1 = $arg1;
	}

	public function setArg2($arg2)
	{
		$this -> arg2 = $arg2;
	}
}

//
use Publixe\Container;

// Setup container
Container::setName('someclass', 'SomeClass', ['A'], [
	'arg2' => 'B'
]);

// in app...

//
$someinstance = Container::get('someclass');

var_dump($someinstance);

//
$someinstance = Container::getContainer() -> getSomeclass();

var_dump($someinstance);
