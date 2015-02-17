<?php
namespace Intentor\Utilities\NotORM;

/**
 * Creates database connection objects for use with NotORM.
 */
class ConnectionFactory {
	/**
	 * Creates a PDO database connection using configurations from config.php.
	 * @return object
	 */
	public static function createPDO() {
		$encoding = (defined(SITE_ENCODING) ? strtolower(str_replace('-', '', SITE_ENCODING)) : 'utf8');		
		$pdo = new \PDO('mysql:host='.CONN_HOST.';dbname='.CONN_DB.';charset='.$encoding, CONN_USER, CONN_PASS);
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$pdo->exec('set names '.$encoding);
		
		return $pdo;
	}
	
	/**
	 * Creates a NotORM database connection.
	 * @param object $pdo PDO database connection.
	 * @return object
	 */
	public static function createNotORM($pdo, $structureClass = '') {
		$structure = null;

		if ($structureClass != '') {
			$structure = new $structureClass();
		} else {
			$structure = new \IntentorStructureConvention (
				$prefix = TABLE_PREFIX
			);
		}
		
		$db = new \NotORM($pdo, $structure);
		
		return $db;
	}
}
?>