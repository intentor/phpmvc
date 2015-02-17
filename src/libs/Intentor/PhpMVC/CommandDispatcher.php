<?php
namespace Intentor\PhpMVC;
	
/**
 * Dispatches a command.
 */
class CommandDispatcher {
	/** Routing command. */
	protected $command;
	
	/**
	 * Class constructor.
	 * @param RountingCommand $command	Command to be dispatched.
	 */
	public function __construct($command) {
		$this->command = $command;
	}
	
	/**
	 * Dispatch the rounting command.
	 */
	public function dispatch() {
		$controller_name = $this->command->get_controller();
		$path = "controllers/$controller_name.php";
		
		if (file_exists($path)) {
			include($path);
			
			$controller_class = ucwords($controller_name).'Controller';
			$controller = new $controller_class($this->command);
			
			if (is_callable(array($controller, $this->command->get_action()))) {
				//Checks if the user is already logged.
				\Auth::check_login();
				
				//Indicates if this request is authorized.
				$is_authorized = true;
				
				//Checks for certain annotations.
				$r = new \ReflectionAnnotatedMethod($controller, $this->command->get_action());
				//Authorize.
				if ($r->hasAnnotation('Authorize')) {
					//First, checks if the application is running on a logged environment.
					if (\Auth::is_auth()) {
						$auth = $r->getAnnotation('Authorize');
					
						//Checks if there's any permissions to be evaluated.
						if (sizeof($auth->value) > 0) {
							$is_authorized = false;
							
							//Having permissions, check if the current user has at least one of the permissions.
							foreach ($auth->value as $perm) {
								if (\Auth::has_permission($perm)) {
									$is_authorized = true;
									break;
								}
							}
						}
					} else {
						$is_authorized = false;
					}
				}
				//Layout.
				if ($r->hasAnnotation('Layout')) {	
					$layout = $r->getAnnotation('Layout');
					$controller->set_layout($layout->value);
				}
				
				if ($is_authorized) {
					$block_pipeline = false;
					
					//Estando autorizado, verifica por anotações de ação.
					foreach ($r->getAllAnnotations() as $a) {
						if (is_a($a, "ActionAnnotation")) {
							if ($a->execute()) {
								$block_pipeline = true;
								break;
							}
						}
					}
					
					if (!$block_pipeline) {
						$params = $this->command->get_parameters();
						call_user_func_array(array($controller, $this->command->get_action()), $params);
					}
				} else {
					header('Location: '.get_url(URL_LOGIN.'?origin='.get_cur_url_from_root()));
				}
			}
		} else {
			header('Location: '.get_url(URL_HTTP404));
		}
	}
}