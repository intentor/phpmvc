<?php
/**
 * Annotation for comparing the field that has the annotation to field with ID $to.
 * <p>Can be used on input text and password, textarea and dropdowns.<p>
 * @Target("property")
 */
class Compare extends Annotation {
	/** Field to compare. */
	public $to;
	/** Message to be displayed. */
	public $message;
}