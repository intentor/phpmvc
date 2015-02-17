<?php
namespace Intentor\PhpMVC;

use Intentor\Utilities\NotORM\ConnectionFactory;

/**
 * Database object.
 */
class DatabaseConnection {
	/** Database connection. */
	public $pdo;
	/** NotORM connection. */
	public $orm;
	
	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->pdo = ConnectionFactory::createPDO();
		$this->orm = ConnectionFactory::createNotORM($this->pdo, STRUCTURE_CONVENTION_CLASS);
	}

	/**
	 * Gets the last inserted ID on a MySQL database.
	 * @return int
	 */
	public function get_last_id() {
		$row = $this->pdo->query('SELECT LAST_INSERT_ID() AS ID')->fetch();
		return $row['ID'];
	}
}