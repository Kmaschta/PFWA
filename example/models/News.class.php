 <?php
/**
 * Modele
 *
 * Classe Modele
 *
 * @author Maschtaler Kevin
 * @version 0.1
 * @package models
 *
 * @license GPLv3
 * @license http://www.gnu.org/licenses/gpl.html
 */

class News extends PFWA_Model {
	static $table_name = "news";
	static $primary_key = 'id';

	static $validates_presence_of = array(
		array('title'),
		array('author'),
		array('content'),
		array('date')
	);
	
	static $validates_size_of = array(
		array('title', 'maximum' => 50),
		array('author', 'maximum' => 30)
	);
}