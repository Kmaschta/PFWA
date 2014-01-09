<?php

/**
 * PFWA_Data
 *
 * Abstract data
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package data
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */
date_default_timezone_set("UTC");

abstract class PFWA_Data extends PFWA_Object {

	/**
	 * Class constructor
	 *
	 * @param string $name Data name
	 */		
	protected function __construct($name) {
		parent::__construct();
		$this->latest_update = new DateTime('now');
		$this->content = null;
		$this->name = (string)$name;
	}

	/**
	 * Hydrate data
	 *
	 * @return void
	 */
	abstract public function collectData();
}
