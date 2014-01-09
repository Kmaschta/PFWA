<?php
/**
 * PFWA_Application
 *
 * Application PFWA
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package global
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Application extends PFWA_Object {
	const APP_DEFAULT_NAME = "My_PFWA_Application";

	const EN_US = "en";
	const FR_FR = "fr";
	const DE_DE = "de";

	const DEFAULT_THEME = "main";
	const DEFAULT_CHARSET = "utf-8";
	const DEFAULT_DB_CHARSET = "utf8";
	const DEFAULT_DB_NAME = "database";
	const DEFAULT_DEBUG_MODE = 0;
	const TEMPLATE_ENGINE_LOADER = "./lib/Twig/Autoloader.php";

	const PHP_CLASS_EXT = ".class.php";
	const TWIG_CLASS_EXT = ".twig";

	const FRONTCONTROLLER_PATH = "controllers/";
	const MODELS_PATH = "models/";
	const TEMPLATES_PATH = "views/templates/";
	const CACHE_PATH = "views/cache/";
	const PUBLIC_PATH = "public/";
	const CSS_PATH = "public/css/";
	const JS_PATH = "public/js/";
	const IMG_PATH = "public/img/";

	const DATE_FORMAT_EN = "Y-m-d";
	const DATE_FORMAT_FR = "d/m/Y";

	/**
	 * Class constructor
	 * 
	 * @param string $name Name of application
	 * @param string $path Path to application
	 * @param array $configFile Files loaded for configure the application
	 */
	public function __construct($name = self::APP_DEFAULT_NAME, $path = "", array $configFile = null) {
		parent::__construct();
		$this->PHP_versions_remedial_func();

		// Base attributs
		$this->name = (string)$name;
		$this->path = (string)$path;
		$this->lang = static::EN_US;
		$this->theme = static::DEFAULT_THEME;
		$this->charset = static::DEFAULT_CHARSET;
		$this->debug = static::DEFAULT_DEBUG_MODE;
		$this->template_engine_loader = static::TEMPLATE_ENGINE_LOADER;
		$this->date_format = array(
			static::EN_US => static::DATE_FORMAT_EN,
			static::FR_FR => static::DATE_FORMAT_FR,
			static::DE_DE => static::DATE_FORMAT_FR
		);

		// Path and extensions
		$this->php_class_ext = static::PHP_CLASS_EXT;
		$this->twig_class_ext = static::TWIG_CLASS_EXT;
		$this->frontcontroller_path = static::FRONTCONTROLLER_PATH;
		$this->models_path = static::MODELS_PATH;
		$this->templates_path = static::TEMPLATES_PATH;
		$this->cache_path = static::CACHE_PATH;
		$this->public_path = static::PUBLIC_PATH;
		$this->css_path = static::CSS_PATH;
		$this->js_path = static::JS_PATH;
		$this->img_path = static::IMG_PATH;

		// Database attributs
		$this->db_default = static::DEFAULT_DB_NAME;
		$this->db_charset = static::DEFAULT_DB_CHARSET;
		$this->db_connections = array();

		// Objects instanciations
		$this->request = PFWA_Request::getInstance($this);
		$this->response = PFWA_Response::getInstance($this);
		$this->router = PFWA_Router::getInstance($this);
		$this->datahandler = PFWA_DataHandler::getInstance($this);

		// Configurations load
		$configFile = ($configFile === null) ? array("./config/". $this->name .".ini") : $configFile;
		$this->config = PFWA_Config::getInstance($this, $configFile);
		$this->config->loadConf();

		// Setting debug mode and timezone
		$this->debug = ($this->debug == true) ? true : false;
		if($this->debug)
			error_reporting(E_ALL);
		else
			error_reporting(0);
		date_default_timezone_set("UTC");
	}

	/**
	 * Run the application
	 * 
	 * @return void
	 */
	public function run() {
		try {
			$this->controller = $this->getController();
			$this->template_engine = $this->loadTemplateEngine();
			$this->orm = $this->loadORM();
			$this->response->page = $this->controller->execute();
			if($this->controller->is_displayable())
				$this->response->send();
			exit(0);
		} catch(Exception $e) {
			$code = $this->response->redirectError($e);
			exit($code);
		}
	}

	/**
	 * Display the app for debug
	 *
	 * @return string <pre>print_r(PFWA_Application $app)</pre>
	 */
	public function __toString() {
		return ($this->debug) ? "<pre>".print_r($this, true)."</pre>" : "";
	}

	/**
	 * Get the controller associate to the selected route
	 *
	 * @return PFWA_BackController The controller with module and action appropriate to the actual route
	 */
	private function getController() {
		$matchedRoute = $this->router->getRoute();
		$controllerClass = $matchedRoute->module ."Controller";
		$file = $this->frontcontroller_path . $controllerClass . $this->php_class_ext;
		if(file_exists($file))
		{
			require_once($file);
			return new $controllerClass($this, $matchedRoute->module, $matchedRoute->action);
		}
		else
			throw new RuntimeException("Le fichier \"". $controllerClass . $this->php_class_ext ."\" est introuvable");
	}

	/**
	 * Load Twig template engine for application
	 *
	 * @return Twig_Environment template engine used by application
	 */
	private function loadTemplateEngine() {
		Twig_Autoloader::register(true);
		$template_loader = new Twig_Loader_Filesystem($this->templates_path
													. $this->theme . DIRECTORY_SEPARATOR
													. strtolower($this->controller->module) . DIRECTORY_SEPARATOR);
		return new Twig_Environment($template_loader, array(
			// "cache" => ($this->debug) ? false : $this->cache_path,
			"cache" => false,
			//"charset" => $this->charset,
			"debug" => $this->debug,
			"auto_reload" => $this->debug,
			"strict_variables" => $this->debug,
			"autoescape" => true,
			"optimizations" => 0 // Inutile
		));
	}

	/**
	 * Load "PHP activerecord" ORM for PFWA_Application
	 *
	 * @return ActiveRecord ORM used by application
	 */
	private function loadORM() {
		$orm = ActiveRecord\Config::instance();
		$orm->set_model_directory(str_replace('/', '', $this->models_path));
		$orm->set_filename_extension('.class.php');
		$orm->set_connections($this->db_connections);
		$orm->set_default_connection($this->db_default);
		return $orm;
	}

	/**
	 * Correctionnal fonction for PHP < 5.3
	 */
	private function PHP_versions_remedial_func() {
		// ob_get_clean function implemented since PHP4.3
		if (!function_exists("ob_get_clean")) {
    		function ob_get_clean() {
        		$ob_contents = ob_get_contents();
        		ob_end_clean();
        		return $ob_contents;
   	 		}
		}
	}
}