<?php

/**
 * PFWA_Data_Intern
 *
 * Intern data
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package data
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Data_Intern extends PFWA_Data {

	/**
	 * Class constructor
	 *
	 * @param string $name Data name
	 * @param mixed $data Data content
	 */
	public function __construct($name, $data = null) {
		parent::__construct($name);
		if($data != null) $this->collectData($data);
	}

	/**
	 * Hydrate data
	 *
	 * @param  mixed $data Data content
	 *
	 * @return bool Return true if data is correctly hydrated
	 */
	public function collectData($data = null) {
		if($data != null) $this->content = $data;
		$this->latest_update = $this->latest_update = new DateTime('now');
		return true;
	}
}
