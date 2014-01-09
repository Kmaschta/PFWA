<?php

/**
 * PFWA_Data_OPC
 *
 * OPC Data
 *
 * @author Kmaschta <kmaschta@gmail.com>
 * @version 0.1
 * @package data
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

date_default_timezone_set('Europe/Paris');

class PFWA_Data_OPC extends PFWA_Data {
	// Constants
	const DEFAULT_WSDL = "http://SQR1:80/opcxmlda/opcserver.wincc?WSDL";
	const PATH_LIB_NUSOAP = "./lib/NuSOAP/nusoap.php";

	/**
	 * Class constructor
	 *
	 * @param string $name    Data name
	 * @param string $key     Data key (OPC)
	 * @param string $requete OPC request
	 * @param string $wsdl    URL to OPC server
	 */
	public function __construct($name, $key = null, $requete = null, $wsdl = null) {
		parent::__construct($name);

		@include_once(self::PATH_LIB_NUSOAP);

		$this->key = $key;
		$this->wsdl = ($wsdl != null) ? $wsdl : self::DEFAULT_WSDL;
		if($requete === null)
		{
			$requete =	'<Read xmlns="http://opcfoundation.org/webservices/XMLDA/1.0/">';
			$requete .=		'<Options ReturnErrorText="true"';
			$requete .=			'ReturnDiagnosticInfo="true"';
			$requete .=			'ReturnItemTime="true"';
			$requete .=			'ReturnItemName="true"';
			$requete .=			'ReturnItemPath="true"';
			$requete .=			'ClientRequestHandle="XYZ"';
			$requete .=			'LocaleID="" />';
			$requete .=		'<ItemList MaxAge="0">';

			if(!is_array($key))
			{
				$requete .=		'<Items MaxAge="0" ItemName="'. $key .'" ClientItemHandle="14213232" />';
			}
			else
			{
				foreach($key as &$value)
				{
					$requete .=	'<Items MaxAge="0" ItemName="'. $value .'" ClientItemHandle="14213232" />';
				}
			}

			$requete .=		'</ItemList>';
			$requete .=	'</Read>';
		}
		$this->requete = $requete;

		if($key != null) $this->collectData($this->key, $this->requete, $this->wsdl);
		$this->latest_update = $this->latest_update = new DateTime('now');
	}

	/**
	 * Hydrate data
	 *
	 * @param  string $key     Date key (OPC)
	 * @param  string $requete OPC request
	 * @param  string $wsdl    URL to OPC server
	 *
	 * @return bool          Return true if data is correctly hydrated
	 */
	public function collectData($key = null, $requete = null, $wsdl = null) {
		if($key != null) $this->key = $key;
		if($requete != null) $this->requete = $requete;
		if($wsdl != null) $this->wsdl = $wsdl;

		$client = new nusoap_client($this->wsdl, true);
		// echo $client->getError();
		$this->content = $client->call('Read', $this->requete, '', '', false, true);
		$this->latest_update = $this->latest_update = new DateTime('now');
		return true;
	}
}