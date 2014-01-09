<?php
/**
 * PFWA_Config
 *
 * Gère les configurations de l'application
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package global
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Config extends PFWA_Object {
	// Singleton operations
	private static $_instance = null;
	public static function getInstance(PFWA_Application $app, array $tFiles) {
		if(self::$_instance == null)
			self::$_instance = new PFWA_Config($app, $tFiles);
		return self::$_instance;
	}
	public function __clone() {}

	/**
	 * Class constructor
	 *
	 * @param PFWA_Application $app    Instance of application
	 * @param array           $tFiles Array of configuration files paths
	 */
	protected function __construct(PFWA_Application $app, array $tFiles) {
		parent::__construct($app);
		foreach ($tFiles as $f) {
			if(!file_exists($f) || is_dir($f))
				throw new InvalidArgumentException("Un fichier doit être spécifié. (". $f .")");
		}
		$this->files = $tFiles;
	}

	/**
	 * Load settings from all configuration files
	 *
	 * @return void
	 */
	public function loadConf() {
		foreach ($this->files as $f) {
			$tab = parse_ini_file($f, true);
			foreach ($tab as $key => $tValue) {
				switch ($key) {
					case 'Routes':
						foreach ($tValue as $routeName => $routeAttributs) {
							$this->app->router->addRoute(new PFWA_Route($routeName, $routeAttributs));
						}
						break;

					case 'DataBase':
						foreach ($tValue as $db_name => $db_string) {
							if($db_name == 'default')
								$this->app->db_default = $db_string;
							else {
								$db = $this->app->db_connections;
								$db[$db_name] = $db_string . "?charset=" . $this->app->db_charset;
								$this->app->db_connections = $db;
							}
						}
						break;

					case 'Application':
					case 'DataBase':
					default:
						foreach ($tValue as $configVariable => $configValue) {
							$this->app->{$configVariable} = $configValue;
						}
						break;
				}
			}
		}
	}
}