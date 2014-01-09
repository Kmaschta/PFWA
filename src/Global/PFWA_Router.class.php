<?php

/**
 * PFWA_Router
 *
 * Router of application
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package global
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Router extends PFWA_Object {
	/** constant NO_ROUTE, useless for tests */
	const NO_ROUTE = 1;

	// Singleton
	private static $_instance = null;
	public static function getInstance(PFWA_Application $app) {
		if(self::$_instance == null)
			self::$_instance = new PFWA_Router($app);
		return self::$_instance;
	}
	public function __clone() {}

	/**
	 * Class constructor
	 *
	 * @param PFWA_Application $app Instance of application
	 */
	protected function __construct($app) {
		parent::__construct($app);
		$this->routes = array();
	}

	/**
	 * Add a route to the collect
	 *
	 * @param PFWA_Route $route
	 */
	public function addRoute(PFWA_Route $route) {
		if(!in_array($route, $this->routes))
		{
			$tmp = $this->routes;
			$tmp[] = $route;
			$this->routes = $tmp;
		}
	}

	/**
	 * Select a route according to the specified URL
	 *
	 * @param  string $url URL specified to application
	 *
	 * @return PFWA_Route   Selected route hydrated by the appropriate variables
	 */
	public function getRoute($url = null) {
		$url = ($url === null) ? $this->app->request->URI : $url;
		
		foreach($this->routes as $r) {
			/* if($r->match($url) === false) echo "NO MATCH"."<br/>";
			else echo "MATCH : ";
			var_dump($r->match($url));
			echo "<br/>"; */
			if(($varsValues = $r->match($url)) !== false) {
				if($r->hasVars()) {
					$varsNames = $r->varsNames;
					$listVars = array();

					foreach($varsValues as $key => $match) {
						if($key != 0) {
							$listVars[$varsNames[$key - 1]] = $match;
						}
					}

					$r->setVars($listVars);
				}
				return $r;
			}
		}
		throw new RuntimeException("Aucune route ne correspond à l'URI spécifié : ". $url, self::NO_ROUTE); 
	}
}