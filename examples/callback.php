<?php

require_once __DIR__ . '/../src/DI/IContainer.php';
require_once __DIR__ . '/../src/DI/Container.php';

use Publixe\Container;

class Test1 {};


$container = new Publixe\DI\Container();

$container -> set(Test1::class);



var_dump(is_callable($container));

$instance = $container -> get(Test1::class);

var_dump($instance);

function test(callable $c) {
	echo "ok\n";
};


// As callback
$callback = $container;

$instance = $callback(Test1::class);

test($callback);

var_dump($instance);


