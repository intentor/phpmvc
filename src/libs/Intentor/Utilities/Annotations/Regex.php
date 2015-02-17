<?php
/**
 * Annotation for using regex on fields.
 * @Target("property")
 */
class Regex extends Annotation {
	/** Pattern to be evaluated. */
	public $pattern;
	/** Message to be showed on validation error. */
	public $message;
}