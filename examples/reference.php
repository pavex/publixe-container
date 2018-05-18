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

	public function setArg3($arg3)
	{
		$this -> arg3 = $arg3;
	}
}

class AnotherClass {

	private $obj;

	public function __construct($obj)
	{
		$this -> obj = $obj;
	}
}


//
use Publixe\Container;

// Setup container
Container::set(SomeClass::class, ['A'], [
	'arg2' => 'B'
]);

Container::setName('someclass', SomeClass::class, ['A'], [
	'arg2' => 'B',
	'arg3' => SomeClass::class
]);

Container::set(AnotherClass::class, [SomeClass::class]);

//
$someinstance = Container::getContainer() -> getSomeclass();
var_dump($someinstance);

$anotherinstance = Container::getContainer() -> get(AnotherClass::class);
var_dump($anotherinstance);
