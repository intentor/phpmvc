<?php
use Intentor\Utilities\Helpers;

/**
 * Intentor's structure convention for NotORM.
 * It's considered that table names can be in either Portuguese or English.
 * 	- Table names always on plural;
 *  - IDs as id_[table name on singular];
 *  - FKs as id_[table name on singular]];
 *  - If the table name has at least one "-", uses its name as its ID.
 */
class IntentorStructureConvention extends NotORM_Structure_Convention {
	/**
	 * Creates a new Intentor structure convention.
	 * @param string prefix for all tables.
	*/
	function __construct($prefix = '') {
		$this->primary = '%s_id';
		$this->foreign = '%s_id';
		$this->table = '%s';
		$this->prefix = $prefix;
	}
	
	protected function getColumnFromTable($name) {		
		//Remove the prefix, if there's any.
		if (starts_with($name, $this->prefix)) {
			$name = substr($name, strlen($this->prefix));
		}
		
		//If the name of the table without the prefix has at least one "-", uses all the word as its ID.
		if (substr_count($name, '_') === 0) {
			if (ends_with($name, 'as')) {
				$name = replace_end($name, 'as', 'a');
			} else if (ends_with($name, 'os')) {
				$name = replace_end($name, 'os', 'o');
			} else if (ends_with($name, 'oes')) {
				$name = replace_end($name, 'oes', 'ao');
			} else if (ends_with($name, 'eis')) {
				$name = replace_end($name, 'eis', 'el');
			} else if (ends_with($name, 's')) { //Valid for English words.
				$name = replace_end($name, 's', '');
			} else if (ends_with($name, 'es')) { //Valid for English words.
				$name = replace_end($name, 'es', '');
			}
		}
		
		return $name;
	}
}