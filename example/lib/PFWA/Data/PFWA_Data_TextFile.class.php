<?php

/**
 * PFWA_Data_TextFile
 *
 * Classe de données fichiers texte
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package data
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Data_TextFile extends PFWA_Data {

	/**
	 * Class constructor
	 *
	 * @param string $name  Data name
	 * @param string $path  File Path
	 * @param string $regex Regex string
	 */
	public function __construct($name, $path = null, $regex = null) {
		parent::__construct($name);
		if($path != null) $this->collectData($path, $regex);
	}

	/**
	 * Hydrate data
	 *
	 * @param  string $path  File Path
	 * @param  string $regex Regex string
	 *
	 * @return boll        Return true if data is correctly hydrated
	 */
	public function collectData($path = null, $regex = null) {
		if($path != null) $this->path = $path;
		if($regex != null && is_array($regex)) $this->regex = $regex;

		$file = @fopen($this->path, 'r');
		if($file === false)
			return false;
		else
		{
			$content = '';
			while(!feof($file))
				$content .= fgets($file);
			fclose($file);
			$content = substr($content, 0, strlen($content) - 1); // Supprime le dernier retour à la ligne

			if($this->regex != null)
				$content = preg_replace($this->regex[0], $this->regex[1], $content);

			$this->content = $content;
		}
		$this->latest_update = $this->latest_update = new DateTime('now');
		return true;
	}
}