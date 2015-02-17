<?php	
namespace Intentor\PhpMVC;

/**
 * Supertype for MVC controllers.
 */
abstract class Controller {
	/** Current request model. */
	public static $current_model;
	/** PHP MVC message error session's name. */
	public static $MESSAGE_ERROR_SESSION = 'phpmvc_msg_error';
	/** PHP MVC message info session's name. */
	public static $MESSAGE_INFO_SESSION = 'phpmvc_msg_info';
	
	/** Routing command. */
	protected $command;
	/** Current controller. */
	protected $controller;
	/** Current view to be rendered. */
	protected $current_view;
	/** Current model. */
	protected $model;
	/** Page title. */
	protected $title;
	/** Script tags. */
	protected $scripts;
	/** Style tags. */
	protected $styles;
	/** Database connection. */
	protected $db;
	/** Layout file to be rendered. */
	protected $layout;

	/**
	 * Class constructor.
	 * @param RoutingCommand $command Command issued to the controller.
	 * @param string $layout Layout to be used for this controller.
	 */
	public function __construct($command) {
		$this->include_lang($command->get_controller());
		$this->command = $command;
		$this->controller = $command->get_controller();
		$this->model = $command->get_model();
		self::$current_model = $this->model;
		$this->db = new DatabaseConnection();
		$this->layout = LAYOUT; //Sets the standard layout.
		$this->styles = '';
		$this->scripts = '';
	}

	/**
	 * Defines the layout for the current controller.
	 * @param string $layout Layout file to be rendered. Can be empty.
	 */
	public function set_layout($layout) {
		$this->layout = $layout;
	}
	
	/**
	 * Get current controller.
	 * @return String Name current controller
	 * */
	public function get_controller() {
		return $this->controller;
	}
	
	/**
	 * Includes the language file, if it exists and there's a language defined.
	 * @param string $controller_name Name of the controller being accessed.
	 */
	private function include_lang($controller_name) {
		$lang_code = cur_lang();
		$path_lang_shared = 'lang/'.$lang_code.'/shared.inc';
		$path_lang_controller = 'lang/'.$lang_code.'/'.$controller_name.'.inc';
			
		//Includes the shared language file.
		if (file_exists($path_lang_shared)) include($path_lang_shared);
		//Includes the current language file.
		if (file_exists($path_lang_controller)) include($path_lang_controller);
	}

	/**
	 * Checks if it's post.
	 * @return boolean TRUE if it's a post, FALSE otherwise.
	 */
	protected function is_post() {
		return ($_SERVER['REQUEST_METHOD'] == 'POST');
	}

	/**
	 * Renders a partial view.
	 * @param string $partial_view_name	View name.
	 */
	protected function partial($partial_view_name) {
		$controller_name = $this->command->get_controller();
		$path = "views/$controller_name/$partial_view_name.php";

		if (file_exists($path)) {
			$this->current_view = $path;
			$this->render_body();
		} else if (file_exists("views/shared/$partial_view_name.php")) {
			$this->current_view = "views/shared/$partial_view_name.php";
			$this->render_body();
		} else {
			$this->redirect(URL_BASE.'error/http404/');
		}
	}

	/**
	 * Redirects to a certain URL.
	 * @param string $url URL to be redirected to.
	 */
	protected function redirect($url) {
		if (strpos($url, "http://") === false && strpos($url, "https://") === false) {
			$url = get_url($url);
		}

		header('Location: '.$url);
	}

	/**
	 * Renders the current page's body.
	 */
	protected function render_body() {
		include($this->current_view);
	}

	/**
	 * Renders a view.
	 * @param string $view_name View name.
	 * @param string $title		Page title.
	 * @param boolean $include_layout=true	Include layout option.
	 */
	protected function view($view_name, $title = "", $include_layout = true) {
		$this->title = $title;
		$controller_name = $this->command->get_controller();
		$path = "views/$controller_name/$view_name.php";

		if (!$include_layout) {
			$this->layout='';
		}

		if (file_exists($path)) {
			$this->current_view = $path;
			//Includes _layout, which will call render_body().
			$this->render_layout($controller_name);
		} else if (file_exists("views/shared/$view_name.php")) {
			$this->current_view = "views/shared/$view_name.php";
			//Includes _layout, which will call render_body().
			$this->render_layout($controller_name);
		} else {
			$this->redirect(URL_BASE.'error/http404/');
		}
	}

	/**
	 * Includes the layout file.
	 * @param string $controller_name Name of the controller in which the layout in being rendered.
	 */
	protected function render_layout($controller_name) {
		//If no layout is informed, just render the body.
		if ($this->layout == '') {
			$this->render_body();
		} else if (file_exists('views/'.$controller_name.'/'.$this->layout)) {
			include('views/'.$controller_name.'/'.$this->layout);
		} else  {
			include('views/shared/'.$this->layout);
		}
	}
	
	/**
	 * Adds an error message.
	 * @param string $message Message to be added.
	 */
	protected function add_message_error($message) {
		if (!isset($_SESSION[self::$MESSAGE_ERROR_SESSION])) {
			$_SESSION[self::$MESSAGE_ERROR_SESSION] = array(); 
		} 
			
		$_SESSION[self::$MESSAGE_ERROR_SESSION][] = $message;
	}
	
	/**
	 * Gets any error messages that may exist on the session.
	 * @return string
	 */
	protected function get_messages_error() {
		$message = array();
		
		if (isset($_SESSION[self::$MESSAGE_ERROR_SESSION])) {
			$message = $_SESSION[self::$MESSAGE_ERROR_SESSION];
			unset($_SESSION[self::$MESSAGE_ERROR_SESSION]);
		}
		
		return $message;
	}

	/**
	 * Adds an info message.
	 * @param string $message Message to be added.
	 */
	protected function add_message_info($message) {
		if (!isset($_SESSION[self::$MESSAGE_INFO_SESSION])) {
			$_SESSION[self::$MESSAGE_INFO_SESSION] = array();
		} 
		
		$_SESSION[self::$MESSAGE_INFO_SESSION][] = $message;
	}
	
	/**
	 * Gets any info messages that may exist on the session.
	 * @return string
	 */
	protected function get_messages_info() {
		$message = array();
		
		if (isset($_SESSION[self::$MESSAGE_INFO_SESSION])) {
			$message = $_SESSION[self::$MESSAGE_INFO_SESSION];
			unset($_SESSION[self::$MESSAGE_INFO_SESSION]);
		}
		
		return $message;
	}	

	/**
	 * Adds a stylesheet (CSS) file to the page.
	 * @param string $file
	 */
	protected function add_style($file) {
		$this->styles .= sprintf('<link rel="stylesheet" href="%s" type="text/css" media="all" />', URL_BASE."content/$file");
	}

	/**
	 * Adds a script (JS) file to the page.
	 * @param string $file
	 */
	protected function add_script($file) {
		$this->scripts .= sprintf('<script src="%s" type="text/javascript"></script>', URL_BASE."scripts/$file");
	}
}