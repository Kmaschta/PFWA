<?php
/**
 * PFWA_Request
 *
 * Entry point of application
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package global
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Request extends PFWA_Object {
	// Singleton
	private static $_instance = null;
	public static function getInstance(PFWA_Application $app) {
		if(self::$_instance == null)
			self::$_instance = new PFWA_Request($app);
		return self::$_instance;
	}
	public function __clone() {}

	/**
	 * Class constructor
	 *
	 * @param PFWA_Application $app Instance of application
	 */
	protected function __construct(PFWA_Application $app) {
		parent::__construct($app);
		$this->loadRequest();
	}

	/**
	 * Load all server, client, GET, POST, cookies informations. It's the entry point of application.
	 *
	 * @return void
	 */
	public function loadRequest() {
		$this->protocol = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
		$this->host = $this->protocol ."://". $_SERVER['HTTP_HOST'] . $this->app->path;
		preg_match('#^'. $this->app->path .'(.*)$#i', $_SERVER['REQUEST_URI'], $matches);
		$this->URI = $matches[1];
		$this->URL = $this->host . $this->URI;
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->userIP = $_SERVER['REMOTE_ADDR'];
		$this->userPort = $_SERVER['REMOTE_PORT'];
		
		$data_handler = PFWA_DataHandler::getInstance($this->app);

		foreach($_POST as $key => $value)
		{
			$data_handler->addData(PFWA_DataHandler::DATA_POST, array(
				"name" => "Request_". $key,
				"key" => $key
			));
			$this->{$key} = $data_handler->datas->{"Request_". $key};
			$data_handler->delData("Request_". $key);
		}
		
		foreach($_GET as $key => $value)
		{
			$data_handler->addData(PFWA_DataHandler::DATA_GET, array(
				"name" => "Request_". $key,
				"key" => $key
			));
			$this->{$key} = $data_handler->datas->{"Request_". $key};
			$data_handler->delData("Request_". $key);
		}

		foreach($_COOKIE as $key => $value)
		{
			$data_handler->addData(PFWA_DataHandler::DATA_Intern, array(
				"name" => "Request_". $key,
				"content" => $value
			));
			$this->{$key} = $data_handler->datas->{"Request_". $key};
			$data_handler->delData("Request_". $key);
		}
	}
}