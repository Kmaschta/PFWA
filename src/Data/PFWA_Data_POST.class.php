<?php

/**
 * PFWA_Data_POST
 *
 * POST Data
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package data
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Data_POST extends PFWA_Data {

	/**
	 * Class constructor
	 *
	 * @param string $name Data name
	 * @param string $key  POST key
	 */
	public function __construct($name, $key = null) {
		parent::__construct($name);
		if($key != null) $this->collectData($key);
	}

	/**
	 * Hydrate data
	 *
	 * @param  string $key POST key
	 *
	 * @return bool      Return true if data is correctly hydrated
	 */
	public function collectData($key = null) {
		if($key != null) $this->key = $key;
		if(isset($_POST[$this->key]) && $_POST[$this->key] != null)
			$this->content = $_POST[$this->key];
		else return false;
		$this->latest_update = $this->latest_update = new DateTime('now');
		return true;
	}
}