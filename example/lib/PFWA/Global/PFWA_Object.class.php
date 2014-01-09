<?php

/**
 * PFWA_Object
 *
 * General object of application
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package global
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Object
{
	/**
	 * Array which contains attributs of object
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Class constructor
	 *
	 * @param PFWA_Application $app Instance of application
	 */
	protected function __construct(PFWA_Application $app = null) {
		$this->attributes = array();
		if($app !== null)
			$this->attributes['app'] = $app;
	}

	/**
	 * Class destructor
	 */
	public function __destruct() {
		unset($this->attributes);
	}

	/**
	 * Class setter
	 *
	 * @param string $attr  Attribute name
	 * @param mixed $value Attribute value
	 */
	public function __set($attr, $value) {
		if($attr != null)
		{
			if($value === null && array_key_exists($attr, $this->attributes))
				unset($this->attributes[$attr]);
			else
				$this->attributes[$attr] = $value;
			return true;
		}
		else throw new InvalidArgumentException("L'attribut ne peut être null.");
	}

	/**
	 * Class getter
	 *
	 * @param  string $attr Attribut name
	 *
	 * @return mixed       Return the value of attribute
	 */
	public function __get($attr) {
		if($attr != null && array_key_exists($attr, $this->attributes))
		{
			return $this->attributes[$attr];
		}
		else return null;
	}

	/**
	 * Class toString
	 *
	 * @return string Return a print_r of object
	 * @see  print_r
	 */
	public function __toString() {
		return "<pre>".print_r($this, true)."</pre>";
	}
}