<?php

/**
 * PFWA_Data_GET
 *
 * Classe de données GET
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package data
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Data_GET extends PFWA_Data {

	public function __construct($name, $key = null) {
		parent::__construct($name);
		if($key != null) $this->collectData($key);
	}

	public function collectData($key = null) {
		if($key != null) $this->key = $key;
		if(isset($_GET[$this->key]) && $_GET[$this->key] != null)
			$this->content = $_GET[$this->key];
		else return false;
		$this->latest_update = time();
		return true;
	}
}
