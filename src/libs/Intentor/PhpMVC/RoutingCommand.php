<?php
namespace Intentor\PhpMVC;

/**
 * Routing command.
 */
class RoutingCommand {
	/** Language on the URL. */
	protected $language;
	/** Controller name. */
	protected $controller;
	/** Action name. */
	protected $action;
	/** Action parameters. */
	protected $parameters = array();
	/** URL's GET parameters. */
	protected $url_get_parameters;
	
	/**
	 * Class constructor.
	 * @param string $controller Command's controller.
	 * @param string $action Command's action.
	 * @param array $parameters Command's parameters.
	 * @param array $url_get_parameters URL's GET parameters.
	 */
	public function __construct($language, $controller, $action, $parameters, $url_get_parameters) {
		$this->language = format_lang($language);
		$this->controller = strtolower($controller);
		$this->action = strtolower($action);
		$this->parameters = $parameters;
		$this->url_get_parameters = $url_get_parameters;
	}
	
	/**
	 * Returns if the command has a language entry.
	 * @return boolean
	 */
	public function has_language() {
		return (is_lang($this->language));
	}
	
	/**
	 * Returns if the command has parameters.
	 * @return boolean
	 */
	public function has_parameters() {
		return (sizeof($this->parameters) > 0);
	}
	
	/**
	 * Returns if the command has parameters.
	 * @return boolean
	 */
	public function has_url_get_parameters() {
		return (strlen($this->url_get_parameters) > 3);
	}
	
	/**
	 * Gets the current command's controller name.
	 * @return string
	 */
	public function get_language() {
		return $this->language;
	}
	
	/**
	 * Gets the current command's controller name.
	 * @return string
	 */
	public function get_controller() {
		return $this->controller;
	}

	/**
	 * Gets the current command's action name.
	 * @return string
	 */
	public function get_action() {
		return $this->action;
	}
	
	/**
	 * Gets the current command's parameters.
	 * @return string
	 */
	public function get_parameters() {
		return $this->parameters;
	}
	
	/**
	 * Gets the get URL parameters.
	 * @return string;
	 */
	public function get_url_get_parameters() {
		return $this->url_get_parameters;
	}
	
	/**
	 * Gets a URL from the command.
	 * <p>The URL is always returned with a language parameter.</p>
	 * @return string
	 */
	public function get_command_url() {
		$language = ($this->has_language() ? $this->language : cur_lang());
		$controller = ($this->controller == 'home' && $this->action == 'index' && !$this->has_parameters() ? '' : $this->controller);
		$action = ($this->action == 'index' && !$this->has_parameters() ? '' : $this->action);
		$controller_parameters = ($this->has_parameters() ? implode('/', $this->parameters) : '/');
		$get_parameters = ($this->has_url_get_parameters() ? '?'.$this->url_get_parameters : '');	
		
		return DIRECTORY_ROOT.$language.($controller == '' ? '' : '/'.$controller).($action == '' ? '' : '/'.$action).($controller_parameters == '/' ? '' : '/'.$controller_parameters).$get_parameters;
	}
	
	/**
	 * Gets the current command's parameters.
	 * @return string
	 */
	public function get_model() {
		$model = null;
		
		//Includes the shared model, if exists.
		$path_shared = 'models/shared.php';
		if (file_exists($path_shared)) include($path_shared);
		
		//Checks if the models' file for the current controller exists.
		$path_current_model = 'models/'.$this->controller.'.php';
		
		if (file_exists($path_current_model)) {
			//If the file exists, checks if the class for the current action exists.
			include($path_current_model);
			$class_name = ucwords($this->controller).ucwords($this->action)."Model";
			
			if (class_exists($class_name)) {
				//Creates the class and populates its properties.
				$model = new $class_name();
				
				//Iterates through all request values and check for properties with the same name.
				foreach ($_REQUEST as $key => $value) {
					if (property_exists($model, $key)) {
						$model->$key = $value;
					}
				}
			}					
		}
		
		return $model;
	}
}