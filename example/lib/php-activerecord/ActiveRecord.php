<?php
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300)
	die('PHP ActiveRecord requires PHP 5.3 or higher');

define('PHP_ACTIVERECORD_VERSION_ID','1.0');

require 'lib/Singleton.php';
require 'lib/Config.php';
require 'lib/Utils.php';
require 'lib/DateTime.php';
require 'lib/Model.php';
require 'lib/Table.php';
require 'lib/ConnectionManager.php';
require 'lib/Connection.php';
require 'lib/SQLBuilder.php';
require 'lib/Reflections.php';
require 'lib/Inflector.php';
require 'lib/CallBack.php';
require 'lib/Exceptions.php';


spl_autoload_register('activerecord_autoload', true, true);

function activerecord_autoload($class_name)
{
	$path = ActiveRecord\Config::instance()->get_model_directory();
	$ext = ActiveRecord\Config::instance()->get_filename_extension();
	$root = realpath(isset($path) ? $path : '.');

	if (($namespaces = ActiveRecord\get_namespaces($class_name)))
	{
		$class_name = array_pop($namespaces);
		$directories = array();

		foreach ($namespaces as $directory)
			$directories[] = $directory;

		$root .= DIRECTORY_SEPARATOR . implode($directories, DIRECTORY_SEPARATOR);
	}

	// Modifié pour les besoins de mon projet
	// $file = "$root/$class_name.php";
	$file = "$root/$class_name";
	$file = $root . DIRECTORY_SEPARATOR .$class_name . $ext;

	if (file_exists($file))
		require $file;
}
?>
