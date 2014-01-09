<?php
/**
 * PFWA_Response
 *
 * Exit point of application
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package global
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_Response extends PFWA_Object {
	// Singleton
	private static $_instance = null;
	public static function getInstance(PFWA_Application $app) {
		if(self::$_instance == null)
			self::$_instance = new PFWA_Response($app);
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
		$this->page = "";
	}

	/**
	 * Display the page, send the HTML code
	 *
	 * @return void
	 */
	public function send() { echo $this->page; }

	/**
	 * Set a cookie (like 'setcookie' function in PHP)
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param integer $expire
	 * @param string  $path
	 * @param string  $domain   
	 * @param boolean $secure 
	 * @param boolean $httpOnly
	 * @see  http://php.net/setcookie
	 */
	public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
	{
		setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
	}

	/**
	 * Redirect user to an other page
	 *
	 * @param  string $locate URL to redirect
	 *
	 * @return void
	 */
	public function redirect($locate) {
		header("Location : ". $locate);
		exit();
	}

	/**
	 * Redirect user to an error page
	 *
	 * @param  Exception $e Exception found by application
	 *
	 * @return int Error code
	 * @todo  Improve the controller system for redirect to an error controller
	 */
	public function redirectError(Exception $e) {
		$page = '<meta charset="'. $this->app->charset .'" />';
		$eName = get_class($e);
		$code = 0;
		switch ($eName) {
			case 'InvalidArgumentException':
				$code = 500;
				header("HTTP/1.0 ". $code ." Internal Server Error");
				$page .= "<h2>". $code ." Error : Internal Server Error</h2>";
				break;

			case 'Twig_Error_Loader':
				$code = 501;
				header("HTTP/1.0 ". $code ." Not Implemented");
				$page .= "<h2>". $code ." Error : Not Implemented</h2>";
				break;

			case 'RuntimeException':
				$code = 404;
				header("HTTP/1.0 ". $code ." Not found");
				$page .= "<h2>". $code ." Error : Not found</h2>";
				break;
			
			case 'Exception':
			default:
				$code = 418;
				header("HTTP/1.0 ". $code ." I'm teapot");
				$page .= "<h2>". $code ." Error : I'm teapot</h2>";
				break;
		}
		if($this->app->debug) {
			$page .= '['. $eName .':'. $e->getCode() .'] '. $e->getMessage() ."<br/>";
			$page .= "<i>In <strong>\"". $e->getFile() ."\"</strong> at <strong>". $e->getLine() ."</strong></i>";
		}
		$this->page = $page;
		$this->send();
		return $code;
	}
}