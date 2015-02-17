<?php
use Intentor\Utilities\Helpers;

/**
 * Structure convention of NotORM.
 * 	- Table names always in the singular.
 *  - IDs like [table name]_id
 *  - FKs like [table name]_id
 *  - In case the table have a least one "-", your full name is used as ID.
 */
class StructureConvention extends NotORM_Structure_Convention {
	/**
	 * Creates a new Intentor structure convention.
	 * @param string prefix for all tables.
	 */
	function __construct() {
		$this->primary = '%s_id';
		$this->foreign = '%s_id';
		$this->table = '%s';
		$this->prefix = '';
	}

	public function getColumnFromTable($name) {
		//Remove the prefix, if there's any.
		if (starts_with($name, TABLE_PREFIX)) {
			$name = substr($name, strlen(TABLE_PREFIX));
		}

		return $name;
	}
}