<?php
use Intentor\PhpMVC\BaseController;

/**
 * Helper for HTML actions.
 */
class Html {
	// ==WITHOUT MODEL==
	
	/**
	 * Generates a label.
	 * 
	 * @example Html::label('text')
	 * @param string $for
	 *        	Name/ID of the control related to the label.
	 * @param string $text
	 *        	Text for the label.
	 * @param array $attr
	 * 			Additional attributes
	 */
	public static function label($for, $text, $attr = array()) {
		// label for - text
		$html = '<label id="%s" for="%s" %s>%s</label>';
		echo (sprintf($html, 'lbl_' . $for, $for, self::get_attr($attr), $text));
	}
	
	/**
	 * Generates a dropdown list from a certain list.
	 * 
	 * @example Html::dropdown('name', array(array('value' => 'text'), array('value' => 'text'), true, 'value', array('custom:id' => '1'))
	 * @param $name Name
	 *        	of the element.
	 * @param array $list
	 *        	Array containing the value/text pairs.
	 * @param boolean $include_empty
	 *        	Text to be displayed.
	 * @param string $selected
	 *        	Value to be selected.
	 * @param array $attr
	 *        	Additional attributes.
	 */
	public static function dropdown($name, $list, $include_empty = false, $selected = '', $attr = array()) {
		// name - attributes - options.
		$html = '<select name="%s" id="%s" %s>%s</select>';
		$options = ($include_empty ? '<option value="">---</option>' : '');
		
		// Iterate through items.
		foreach($list as $item) {
			$value = key($item);
			$options .= sprintf('<option value="%s" %s>%s</option>', $value, ($value == $selected ? 'selected="selected"' : ''), $item [$value]);
		}
		
		echo (sprintf($html, $name, $name, self::get_attr($attr), $options));
	}
	
	/**
	 * Generates a input radio.
	 * 
	 * @example Html::radio('name', array('Yes' => '1', 'No' => '0'), array('custom:id' => '1'))
	 * @param $name Name
	 *        	of the element.
	 * @param $values Values
	 *        	of the radio buttons, incluing labels.
	 * @param array $attr
	 *        	Additional attributes.
	 */
	public static function radio($name, $values, $attr = array()) {
		$html = '';
		
		foreach($values as $key => $value) {
			$id = $name + "_" + $value;
			$html .= sprintf('<label for="%s">%s</label>', $id, $key);
			$html .= sprintf('<input type="radio" id="%s" name="%s" value="%s" %s/>', $id, $name, $value, self::get_attr($attr));
		}
		
		echo ($html);
	}
	
	/**
	 * Generates a input checkbox.
	 * 
	 * @example Html::checkbox('name', array('custom:id' => '1'))
	 * @param $name Name
	 *        	of the element.
	 * @param array $attr
	 *        	Additional attributes.
	 */
	public static function checkbox($name, $attr = array()) {
		self::input('checkbox', $name, $attr);
	}
	
	/**
	 * Generates a input hidden.
	 * 
	 * @example Html::hidden('name', array('custom:id' => '1'))
	 * @param $name Name
	 *        	of the element.
	 * @param array $attr
	 *        	Additional attributes.
	 */
	public static function hidden($name, $attr = array()) {
		self::input('hidden', $name, $attr);
	}
	
	/**
	 * Generates a input password.
	 * 
	 * @example Html::password('name', array('custom:id' => '1'))
	 * @param $name Name
	 *        	of the element.
	 * @param array $attr
	 *        	Additional attributes.
	 */
	public static function password($name, $attr = array()) {
		self::input('password', $name, $attr);
	}
	
	/**
	 * Generates a input text.
	 * 
	 * @example Html::textbox('name', array('custom:id' => '1'))
	 * @param $name Name
	 *        	of the element.
	 * @param array $attr
	 *        	Additional attributes.
	 */
	public static function textbox($name, $attr = array()) {
		self::input('text', $name, $attr);
	}
	
	/**
	 * Generates a textarea.
	 * 
	 * @example Html::textarea('name', 'text', array('custom:id' => '1'))
	 * @param $name Name
	 *        	of the element.
	 * @param object $text
	 *        	Text to be displayed.
	 * @param array $attr
	 *        	Additional attributes.
	 */
	public static function textarea($name, $text = '', $attr = array()) {
		// name - attributes - text
		$html = '<textarea name="%s" id="%s" %s>%s</textarea>';
		echo (sprintf($html, $name, $name, self::get_attr($attr), htmlentities($text, ENT_QUOTES | ENT_IGNORE)));
	}
	
	/**
	 * Generates a input element.
	 * 
	 * @param string $type
	 *        	Input type.
	 * @param $name Name
	 *        	of the element.
	 * @param array $attr
	 *        	Additional attributes.
	 */
	private static function input($type, $name, $attr = array()) {
		// type - name - attributes
		$html = '<input type="%s" name="%s" id="%s" %s/>';
		echo sprintf($html, $type, $name, $name, self::get_attr($attr));
	}
	
	/**
	 * Gets the attributes in a correct format.
	 * 
	 * @param array $attr
	 *        	Attributes to be formated.
	 * @return string
	 */
	private static function get_attr($attr) {
		if (! is_array($attr))
			return '';
		
		foreach($attr as $key => $val) {
			$attr [$key] = '"' . $val . '"';
		}
		
		return (count($attr) == 0 ? '' : array_implode('=', ' ', $attr));
	}
	
	// ==WITH MODEL==
	
	/**
	 * Generates a validation message box for a certain property in a model.
	 * 
	 * @example Html::validation_message_for('property')
	 * @param string $property Name of the property.
	 * @param string $css_class Element's CSS class(es). By default, it's "validation_message".
	 */
	public static function validation_message_for($property, $css_class = 'validation_message') {
		// Checks if a "Required" or "Validate" annotation exists on the property.
		$r = new ReflectionAnnotatedProperty(BaseController::$current_model, $property);
		if ($r->hasAnnotation('Required') || $r->hasAnnotation('Validate')) {
			$html = '<span id="val_%s" class="%s"></span>';
			echo sprintf($html, $property, $css_class);
		}
	}
	
	/**
	 * Generates a label for a certain property in a model.
	 * 
	 * @example Html::label_for('property')
	 * @param string $property Name of the property.
	 * @param string $css_class Element's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	public static function label_for($property, $css_class = '', $attr = array()) {		
		if (!empty($css_class)) $attr['class'] = $css_class;
		
		$value = $property; // The value of the label is at first with the property name.
		                   
		// Checks if a "Display" annotation exists on the property.
		$r = new ReflectionAnnotatedProperty(BaseController::$current_model, $property);
		if ($r->hasAnnotation('Display')) {
			$value = $r->getAnnotation('Display')->label;
		}
		
		self::label($property, $value, $attr);
	}
	
	/**
	 * Generates a dropdown list for a certain property in a model using a given list.
	 * 
	 * @example Html::dropdown_for('property', array(array('value' => 'text'), array('value' => 'text')), true, array('custom:id' => '1'))
	 * @param string $property Name of the property.
	 * @param array $list Array containing the value/text pairs.
	 * @param boolean $include_empty Text to be displayed.
	 * @param string $css_class Element's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	public static function dropdown_for($property, $list, $include_empty = false, $css_class = '', $attr = array()) {
		if (!empty($css_class)) $attr['class'] = $css_class;
		
		// Checks for annotations on the property.
		$r = new ReflectionAnnotatedProperty(BaseController::$current_model, $property);
		foreach($r->getAllAnnotations () as $a) {
			switch (get_class($a)) {
				case 'Compare': {
						$attr ['custom:compare_to'] = $a->to;
						$attr ['custom:compare_message'] = $a->message;
					} break;
				case 'Required': {
						$attr ['custom:required'] = $a->value;
					} break;
				case 'Validate': {
						$attr ['custom:function'] = $a->function;
						$attr ['custom:message'] = $a->message;
					} break;
			}
		}
		
		self::dropdown($property, $list, $include_empty, BaseController::$current_model->$property, $attr);
	}
	
	/**
	 * Generates a input radio for a certain property in a model.
	 * 
	 * @example Html::radio('property', array('Yes' => '1', 'No' => '0'), array('custom:id' => '1'))
	 * @param string $property Name of the property.
	 * @param $values Values of the radio buttons, incluing labels.
	 * @param string $css_class lement's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	public static function radio_for($property, $values, $css_class = '', $attr = array()) {
		if (!empty($css_class)) $attr['class'] = $css_class;
		
		$html = '';
		foreach($values as $key => $value) {
			$input_attr = $attr;
			
			// If required, indicates it only on the first item.
			if ($html == '') {
				// Checks for annotations on the property.
				$r = new ReflectionAnnotatedProperty(BaseController::$current_model, $property);
				foreach($r->getAllAnnotations () as $a) {
					switch (get_class($a)) {
						case 'Required': {
								$input_attr ['custom:required'] = $a->value;
							} break;
						case 'Validate': {
								$attr ['custom:function'] = $a->function;
								$attr ['custom:message'] = $a->message;
							} break;
					}
				}
			}
			
			// Checks if the current is value is the one to be selected.
			if ($value == BaseController::$current_model->$property) {
				$input_attr ['checked'] = 'checked';
			}
			
			$id = $property + "_" + $value;
			$html .= sprintf('<input type="radio" id="%s" name="%s" value="%s" %s/>', $property, $property, $value, self::get_attr($input_attr));
			$html .= sprintf('<label for="%s">%s</label>', $id, $key);
		}
		
		echo ($html);
	}
	
	/**
	 * Generates a checkbox for a certain property in a model.
	 * <p>It'll be checked if the value of the desired propery is 1 or true.</p>
	 * 
	 * @example Html::checkbox_for('property', array('custom:id' => '1'))
	 * @param string $property Name of the property.
	 * @param string $css_class lement's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	public static function checkbox_for($property, $css_class = '', $attr = array()) {
		self::input_for('checkbox', $property, $css_class, $attr);
	}
	
	/**
	 * Generates a input hidden for a certain property in a model.
	 * 
	 * @example Html::hidden_for('property', array('custom:id' => '1'))
	 * @param string $property Name of the property.
	 * @param string $css_class lement's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	public static function hidden_for($property, $css_class = '', $attr = array()) {
		self::input_for('hidden', $property, $css_class, $attr);
	}
	
	/**
	 * Generates a input password for a certain property in a model.
	 * 
	 * @example Html::password_for('property', array('custom:id' => '1'))
	 * @param string $property Name of the property.
	 * @param string $css_class lement's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	public static function password_for($property, $css_class = '', $attr = array()) {
		self::input_for('password', $property, $css_class, $attr);
	}
	
	/**
	 * Generates a input text for a certain property in a model.
	 * 
	 * @example Html::textbox_for('property', array('custom:id' => '1'))
	 * @param string $property Name of the property.
	 * @param string $css_class lement's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	public static function textbox_for($property, $css_class = '', $attr = array()) {
		self::input_for('text', $property, $css_class, $attr);
	}
	
	/**
	 * Generates a textarea for a certain property in a model.
	 * 
	 * @example Html::textarea_for('property', array('custom:id' => '1'))
	 * @param string $property Name of the property.
	 * @param string $css_class lement's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	public static function textarea_for($property, $css_class = '', $attr = array()) {
		if (!empty($css_class)) $attr['class'] = $css_class;
		
		// Checks for annotations on the property.
		$r = new ReflectionAnnotatedProperty(BaseController::$current_model, $property);
		foreach($r->getAllAnnotations () as $a) {
			switch (get_class($a)) {
				case 'Compare': {
						$attr ['custom:compare_to'] = $a->to;
						$attr ['custom:compare_message'] = $a->message;
					} break;
				case 'Regex': {
						$attr ['custom:regex'] = $a->pattern;
						$attr ['custom:regex_message'] = $a->message;
					} break;
				case 'Required': {
						$attr ['custom:required'] = $a->value;
					} break;
				case 'Validate': {
						$attr ['custom:function'] = $a->function;
						$attr ['custom:function_message'] = $a->message;
					} break;
			}
		}
		
		// Writes the object.
		self::textarea($property, BaseController::$current_model->$property, $attr);
	}
	
	/**
	 * Generates a input element for a certain property in a model.
	 * 
	 * @param string $type Input type.
	 * @param string $property Name of the property.
	 * @param string $css_class lement's CSS class(es).
	 * @param array $attr Additional attributes.
	 */
	private static function input_for($type, $property, $css_class = '', $attr = array()) {
		if (!empty($css_class)) $attr['class'] = $css_class;
		
		// Checks for annotations on the property.
		$r = new ReflectionAnnotatedProperty(BaseController::$current_model, $property);
		foreach($r->getAllAnnotations () as $a) {
			switch (get_class($a)) {
				case 'Compare': {
						if ($type == 'text' || $type == 'password') {
							$attr ['custom:compare_to'] = $a->to;
							$attr ['custom:compare_message'] = $a->message;
						}
					} break;
				case 'DefaultValue': {
						if ($type == 'text') {
							$attr['custom:default_label'] = $a->label;
							$attr['custom:default_color'] = $a->color;
						}
					} break;
				case 'Length': {
						if ($type == 'text' || $type == 'password')
							$attr['maxlength'] = $a->value;
					} break;
				case 'Placeholder': {
						if ($type == 'text' || $type == 'password')
							$attr['placeholder'] = $a->value;
					} break;
				case 'Regex': {
						if ($type == 'text') {
							$attr['custom:regex'] = $a->pattern;
							$attr['custom:regex_message'] = $a->message;
						}
					} break;
				case 'Required': {
						$attr['custom:required'] = $a->value;
					} break;
				case 'Validate': {
						$attr['custom:function'] = $a->function;
						$attr['custom:function_message'] = $a->message;
					} break;
			}
		}
		
		//Sets the value.
		$attr['value'] = htmlentities(BaseController::$current_model->$property, ENT_QUOTES | ENT_IGNORE, SITE_ENCODING);
		
		//If the type is checkbox, checks if it needs to be checked.
		if ($type == 'checkbox' && ($attr['value'] == 1 || $attr['value'] == '1' || $attr['value'] === true)) {
			$attr['checked'] = 'checked';
		}
		
		self::input($type, $property, $attr);
	}
}