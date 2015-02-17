<?php
/**
 * Annotation for showing labels with screen fields.
 * @Target("property")
 */
class Display extends Annotation {
	/** Label to be displayed. */
	public $label;
	/** Description to be displayed. */
	public $description;
}