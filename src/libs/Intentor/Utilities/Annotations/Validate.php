<?php
/**
 * Annotation for validating a field with a custom JavaScript function.
 * <p>The function will receive no parameters and must return a boolean value.</p>
 * @Target("property")
 */
class Validate extends Annotation {
	/** Name of the function to be called, without parenthesis. */
	public $function;
	/** Message to be showed on validation error. */
	public $message;
}