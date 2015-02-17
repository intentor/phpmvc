<?php
use Intentor\Utilities\Helpers;

/**
 * Plural structure convention for NotORM.
 * 	- Table names always on singular;
 *  - IDs as [table name]_id;
 *  - FKs as [table name]_id.
 */
class BasicStructureConvention extends NotORM_Structure_Convention {
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
		if (starts_with($name, $this->prefix)) {
			$name = substr($name, strlen($this->prefix));
		}
		
		return $name;
	}
}