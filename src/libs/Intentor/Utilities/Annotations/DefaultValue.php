<?php
/**
 * Annotation for filling fields with a default value.
 * <p>Valid only on TextBoxes (input=text).</p>
 * @Target("property")
 */
class DefaultValue extends Annotation {
	/** Label to be displayed. */
	public $label;
	/** Color of the text when on default value. */
	public $color;
}