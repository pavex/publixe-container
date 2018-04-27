<?php

	namespace Publixe\DI;
	use Publixe;
	use \ReflectionClass;
	use \LogicException;
	use \BadMethodCallException ;


/**
 * Publixe DI container
 *
 * @author	Pavex
 */
	class Container implements Publixe\DI\IContainer
	{

/** @var array */
		private $config = [];

/** @var array */
		private $instance = [];





/**
 * Configure instance
 * @param string
 * @param array=
 * @param array=
 * @throw LogicException
 */
		public function set($class, $params = [], $setters = [])
		{
			$this -> setName(NULL, $class, $params, $setters);
		}





/**
 * Configure instance by name
 * @param string
 * @param string
 * @param array=
 * @param array=
 * @throw LogicException
 */
		public function setName($name, $class, $params = [], $setters = [])
		{
			$name = $name !== NULL ? $name : $class;
			if (in_array($name, $this -> config)) {
				throw new LogicException(sprintf('Instance %s is already set.', $name));
			}
// Registre
			$config = array($name, $class, $params, $setters);
			$this -> config[$name] = &$config;
		}





/**
 * Update params refereces to another containers
 * @param array
 * @param bool=
 */
		private function putReferences(&$params, $resursive = TRUE)
		{
			foreach ($params as $name => &$value) {
				if (is_string($value)) {
					if (isset($this -> config[$value])) {
						$value = $this -> get($value);
					}
				}
				elseif (is_array($value) && $resursive) {
					$this -> putReferences($value);
				}
			}
		}





/**
 * Check if setter method is exists in class
 * @param Object
 * @param string
 * @param string
 * @param bool
 */
		private function hasSetter($instance, $name, &$method)
		{
			$method = 'set' . ucfirst($name);
			return method_exists($instance, $method);
		}





/** 
 * Create stand-alone instance
 * @param string
 * @return Object
 * @throw LogicException
 * @throw BadMethodCallException
 */
		public function create($name)
		{
			if (!isset($this -> config[$name])) {
				throw new LogicException(sprintf('Instance %s not set.', $name));
			}
			list($name, $class, $params, $setters) = $this -> config[$name];
			$instance = new $class(...$params);
//
			$this -> putReferences($params);
			$this -> putReferences($setters);
// Setup setters if available
			$vars = array_keys(get_object_vars($instance));
			foreach ($setters as $name => $value) {
				if (in_array($name, $vars)) {
					$instance -> {$name} = $value;
				}
				elseif ($this -> hasSetter($instance, $name, $setter)) {
					$instance -> $setter($value);
				}
				else {
					throw new BadMethodCallException(sprintf('Setter %s not defined.', $name));
				}
			}
			return $instance;
		}





/**
 * Get class name from config
 * @param string
 * @param string
 */
		private function getClass($name)
		{
			return $this -> config[$name][1];
		}





/**
 * Check if service is available in container
 * @param string
 * @param string=
 * @return bool
 * @throw LogicException
 */
		public function has($name, $subclass = NULL)
		{
			if (!isset($this -> config[$name])) {
				throw new LogicException(sprintf('Instance `%s` not set.', $name));
			}
			if ($subclass) {
				$class = $this -> getClass($name);
				if ($class != $subclass && !is_subclass_of($class, $subclass)) {
					throw new LogicException(sprintf('Incompatible registred instance %s with %s.', $class, $subclass));
				}
			}
			return TRUE;
		}





/**
 * @param string
 * @param bool
 */
		public function isDefined($name)
		{
			return !empty($this -> config[$name][0]);
		}





/**
 * Return or create instance and store it
 * @param string  Callable name or class name
 * @return Object
 *
 *	$instance = $container -> get('article');
 *	$instance = $container -> get('&article');
 *	$instance = $container -> get('Website\Model\Article');
 *	$instance = $container -> get(Website\Model\Article::class);
 */
		public function get($name)
		{
			$name = preg_replace('/^&/', '', $name);
//
			if (isset($this -> instance[$name])) {
				return $this -> instance[$name];
			}
			$instance = $this -> create($name);

// Insert into store only if callable (not only class without name)
			if ($this -> isDefined($name)) {
				$this -> instance[$name] = $instance;
			}
//
			return $instance;
		}





/**
 * Container overloading methods controller
 * @param string
 * @param Array
 * @throw \BadMethodCallException
 */
		public function __call($method, $params)
		{
			if (preg_match('/^get(.*)$/', $method, $match)) {
				$name = lcfirst($match[1]);
				return $this -> get($name);
			}
			elseif (preg_match('/^create(.*)$/', $method, $match)) {
				$name = lcfirst($match[1]);
				return $this -> create($name);
			}
			elseif (preg_match('/^has(.*)$/', $method, $match)) {
				$name = lcfirst($match[1]);
				return $this -> has($name, isset($params[0]) ? $params[0] : NULL);
			}
			throw new BadMethodCallException(sprintf('Method %s access denied.', $method));
		}


	}

?>