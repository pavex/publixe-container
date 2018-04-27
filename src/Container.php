<?php

	namespace Publixe;
	use Publixe;
	use \LogicException;


/**
 * Publixe DI system container
 *
 * @author	Pavex
 */
	class Container
	{

// Predefined default names of services
		const DEFAULT_ROUTER = '%router';
		const DEFAULT_DATABASE = '%database';
		const DEFAULT_APP = '%app';


/** @type Publixe\Container */
		private static $container;





/**
 * Create system container
 * @throw LogicException
 */
		public static function create()
		{
			if (self::$container) {
				throw new LogicException('System container already created.');
			}
			self::$container = new Publixe\DI\Container();
		}





/**
 * @return Publixe\DI\Container
 */
		public static function getContainer()
		{
			return self::$container;
		}





/**
 * Configure service
 * @param string  Represented by class
 * @param array=  Params for constructor
 * @param array=  Setters
 */
		public static function set(...$args)
		{
			self::$container -> set(...$args);
		}





/**
 * Configure service
 * @param string  Name of service
 * @param string  Represented by class
 * @param array=  Params for constructor
 * @param array=  Setters
 */
		public static function setName(...$args)
		{
			self::$container -> setName(...$args);
		}





/**
 * Check if service is available in container
 * @param string
 * @return bool
 */
		public static function has(...$args)
		{
			return self::$container -> has(...$args);
		}





/**
 * Create or return existing instance from container
 * @param string
 * @return Object
 */
		public static function get($name)
		{
			return self::$container -> get($name);
		}


	}





/**
 * Create system container
 */
	Container::create();


?>