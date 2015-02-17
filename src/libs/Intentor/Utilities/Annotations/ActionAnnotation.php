<?php
/**
 * Annotation for custom actions on request authorization phase.
 * @Target("method")
 */
class ActionAnnotation extends Annotation {
	/**
	 * Executes annotation's action.
	 * @return Boolean value indicating if the flow needs to be stopped.
	 */
	public function execute() {
		
	}
}