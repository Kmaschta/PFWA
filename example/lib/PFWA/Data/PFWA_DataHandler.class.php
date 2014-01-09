<?php

/**
 * PFWA_DataHandler
 *
 * Data manager of application
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package data
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class PFWA_DataHandler extends PFWA_Object {
	// Constants
	const DATA_POST = "POST";
	const DATA_GET = "GET";
	const DATA_OPC = "OPC";
	const DATA_TextFile = "TextFile";
	const DATA_Intern = "Intern";

	// Singleton
	private static $_instance = null;
	public static function getInstance(PFWA_Application $app) {
		if(self::$_instance == null)
			self::$_instance = new PFWA_DataHandler($app);
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
		$this->datas = new stdClass();
		$this->source_list = array(
			self::DATA_POST => array(
				"class" => "PFWA_Data_POST",
				"params" => array("name", "key")),
			self::DATA_GET => array(
				"class" => "PFWA_Data_GET",
				"params" => array("name", "key")),
			self::DATA_TextFile => array(
				"class" => "PFWA_Data_TextFile",
				"params" => array("name", "path", "regex")),
			self::DATA_OPC => array(
				"class" => "PFWA_Data_OPC",
				"params" => array("name", "key", "requete", "wsdl")),
			self::DATA_Intern => array(
				"class" => "PFWA_Data_Intern",
				"params" => array("name", "content"))
		);
	}

	/**
	 * Remove a data to the collect
	 *
	 * @param  string $name Data name
	 *
	 * @return void
	 */
	public function delData($name) {
		unset($this->datas->{$name});
	}

	/**
	 * Add a data to the collect
	 *
	 * @param string $type   Type of data ... see constants
	 * @param mixed $params Params of data
	 */
	public function addData($type, $params)
	{
		// Vérification du type de données
		if(array_key_exists($type, $this->source_list))
		{
			if(is_array($params))
			{
				// Vérification des paramètres
				foreach($params as $key => $value)
				{
					if(!in_array($key, $this->source_list[$type]["params"]))
						return false;
				}

				// Instanciation de la donnée
				$data_class = "PFWA_Data_".$type;
				$data = new $data_class($params["name"]);
				$datas = $this->datas;
				$datas->{$params["name"]} = $data;
				$this->datas = $datas;

				// Instanciations des paramètres de la donnée
				foreach($params as $key => $value)
				{
						$tmp = $this->datas;
						$tmp->{$params["name"]}->{$key} = $value;
						$this->datas = $tmp;
				}

				// Collecte de la donnée
				$data->collectData();
			}
		}
		else return false; // Le type de donnée d'existe pas
	}

	/**
	 * Return selected data
	 *
	 * @param  string $name Data name
	 *
	 * @return PFWA_Data
	 */
	public function getData($name) { return $this->datas->{$name}; }

}
