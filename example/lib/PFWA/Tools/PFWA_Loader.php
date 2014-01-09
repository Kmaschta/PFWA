<?php
/**
 * PFWA_Loader
 *
 * Set includes paths and load (include) files
 *
 * @author Maschtaler Kevin
 * @version 0.1
 * @package tools
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

function update_include_path($pattern, $path) {
	if ($dir = opendir($path)) {
		while(false !== ($file = readdir($dir))) {
			if(preg_match("#".$pattern."#i", $file)) return $path;
			//echo $path.'/'.$file."<br>\n";
			if(is_dir($path.'/'.$file) && !in_array($file, array(".",".."))) {
				//echo $path.'/'.$file."<br>\n";
				set_include_path(get_include_path() . PATH_SEPARATOR . $path . DIRECTORY_SEPARATOR . $file . DIRECTORY_SEPARATOR);
				update_include_path($pattern, $path . DIRECTORY_SEPARATOR . $file);
			}
		}
		closedir($dir);
	}
}

spl_autoload_register('PFWA_autoload', true, false);

function PFWA_autoload($class) {
	// Récupération du chemin du répertoire
	$dir_path = dirname(__FILE__);
	$dir_path = preg_replace("#/lib/PFWA/.+$#i", '', $dir_path);

	$path = update_include_path($class, $dir_path);
	echo $path;

	require_once($class.".class.php");
}