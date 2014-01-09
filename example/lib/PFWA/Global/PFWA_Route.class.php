<?php

/**
 * PFWA_Route
 *
 * Route of application
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package global
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Route extends PFWA_Object {

	/**
	 * Class constructor, call the good function according to arguments.
	 *
	 * @param mixed $arg1
	 * @param mixed $arg2
	 * @param mixed $arg3
	 * @param mixed $arg4
	 * @param mixed $arg5
	 */
	public function __construct($arg1 = null, $arg2 = null, $arg3 = null, array $arg4 = null, $arg5 = null) {
		if(is_array($arg2))
			$this->initFromArray($arg1, $arg2);
		else
			$this->init($arg1, $arg2, $arg3, $arg4, $arg5);
	}

	/**
	 * Initialize the route
	 *
	 * @param  string $name
	 * @param  string $url      Regex string for route url
	 * @param  string $module
	 * @param  string $action
	 * @param  array $varsNames Name of route arguments passed in the urldecode(str)
	 *
	 * @return void
	 */
	private function init($name, $url, $module, $action, array $varsNames = null) {
		$this->name = (string)$name;
		$this->setUrl($url);
		$this->setModule($module);
		$this->setAction($action);
		$this->setVarsNames($varsNames);
		$this->setVars();
	}

	/**
	 * Initialize the route, same 'init' fonction, with an array of argument
	 *
	 * @param  string $name
	 * @param  array  $t    Same arguments passed to 'init' function
	 *
	 * @return void
	 */
	private function initFromArray($name, array $t) {
		$vars = (isset($t['varsNames'])) ? explode(',', $t['varsNames']) : null;
		$this->init($name, $t['url'], $t['module'], $t['action'], $vars);
	}

	/**
	 * Retourne true if the route has vars
	 *
	 * @return boolean true if route has vars
	 */
	public function hasVars() { return ($this->varsNames === null) ? false : true; }

	/**
	 * Check if specified URL correspond to the route
	 *
	 * @param  string $url URL specified to application
	 *
	 * @return mixed Return vars catched in the URL if found it, or false
	 */
	public function match($url) {
		if(preg_match("#^". $this->url ."$#i", $url, $matches))
			return $matches;
		else return false;
	}

	/**
	 * Accessor of 'url' variable
	 *
	 * @param string $url Regex string, can catch vars
	 */
	public function setUrl($url) {
		if(is_string($url))
			$this->url = $url;
		else throw new InvalidArgumentException();
	}

	/**
	 * Accessor of 'module' variable
	 *
	 * @param string $module
	 */
	public function setModule($module) {
		if(is_string($module))
			$this->module = $module;
		else throw new InvalidArgumentException();
	}

	/**
	 * Accessor of 'action' variable
	 *
	 * @param string $action
	 */
	public function setAction($action) {
		if(is_string($action))
			$this->action = $action;
		else throw new InvalidArgumentException();
	}

	/**
	 * Set the vars names
	 *
	 * @param array $vars array of strings
	 */
	public function setVarsNames(array $vars = null) { $this->varsNames = $vars; }

	/**
	 * Assign vars to vars names
	 *
	 * @param array $vars Mixed array
	 */
	public function setVars(array $vars = null) { $this->vars = $vars; }
}