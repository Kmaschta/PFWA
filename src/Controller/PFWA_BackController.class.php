<?php

/**
 * PFWA_BackController
 *
 * BackController of application
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package global
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_BackController extends PFWA_Object {

	/**
	 * Class constructor
	 *
	 * @param PFWA_Application $app    Instance of application
	 * @param string          $module Name of managed module
	 * @param string          $action Name of managed action
	 */
	public function __construct(PFWA_Application $app, $module, $action) {
		parent::__construct($app);
		$this->displayable = true;
		$this->setModule($module);
		$this->setAction($action);
	}
	
	/**
	 * Select and execute the function required by request
	 *
	 * @return string Rendering of page that will be display
	 */
	public function execute() {
		$method = "action". ucfirst($this->action);

		// Preparation of additional informations will be passed to function
		$array = array(
			"public_path" => $this->app->request->host . DIRECTORY_SEPARATOR . $this->app->public_path,
			"root_url" => $this->app->request->host . DIRECTORY_SEPARATOR,
			"lang" => $this->app->lang,
			"date_format" => $this->app->date_format[$this->app->lang]
		);

		// If function exists
		if(is_callable(array($this, $method))) {
			$action_name = $this->app->lang. '_' . strtolower($this->action) ."View" . $this->app->twig_class_ext;
			// Call the function passing the catched informations
			$return_array = $this->{$method}(array_merge($array, (array)$this->app->router->getRoute()->vars));
			// If this controller is displayable
			if($this->is_displayable()) {
				// Render generating
				$render = $this->app->template_engine->render($action_name, (array)$return_array);
				// Return render if display is allowed
				if($render != false)
					return $render;
				else throw new Exception("Erreur pendant le chargement de la vue.");
			}
			else return null;
		}
		else throw new RuntimeException("L'action \"". ucfirst($this->action) ."\" n'existe pas dans le module ". ucfirst($this->module));
	}

	/**
	 * Check if this controller is displayable
	 *
	 * @return boolean
	 */
	public function is_displayable() { return $this->displayable; }

	/**
	 * Set if this controller is displayable
	 *
	 * @param bool $b [description]
	 */
	public function set_display(bool $b) { $this->displayable = $b; }

	/**
	 * Module setter
	 *
	 * @param string $module Module name
	 */
	public function setModule($module) {
		if(is_string($module) && !empty($module))
			$this->module = $module;
		else throw new InvalidArgumentException();
	}

	/**
	 * Action setter
	 *
	 * @param string $action Action name
	 */
	public function setAction($action) {
		if(is_string($action) && !empty($action))
			$this->action = $action;
		else throw new InvalidArgumentException();
	}
}