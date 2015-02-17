<?php
namespace Intentor\PhpMVC;

/**
 * Interpret request URLs.
 */
class UrlInterpreter {
	/** Routing command. */
	protected  $command;
	
	/**
	 * Process the URL interpretation.
	 * @return boolean Boolean value indicating if the interpretation was succeded.
	 */
	public function interpret($url) {
		$res = true;
		$params_pos = strrpos($url, '?');
		$lang_url = '';
		$cur_url = $url;
		$get_params_url = '';
		
		//Checks for GET parameters.
		if ($params_pos) {
			$cur_url = substr($cur_url, 0, $params_pos);
			$get_params_url = substr($cur_url, $params_pos + 1);
		}
		$uri = explode('/', $cur_url);
		$root = explode('/', DIRECTORY_ROOT);
		
		//Slices the array removing URL parts from DIRECTORY_ROOT.
		$uri = array_slice($uri, sizeof($root) - 1);
		
		$command_array = array();
		
		//Analyse each URL part.
		$i = 0;
		foreach ($uri as $part) {
			if ($part != '') {
				//Verifies if the first entry is a language entry.
				if (sizeof($command_array) == 0 && is_lang($part)) {
					//Verifies if the language is supported.
					if (is_lang_supported($part)) {
						//Sets its value as language.
						cur_lang($part);
						$lang_url = format_lang($part);
					} else {
						$res = false;
					}
				} else {
					$command_array[$i++] = $part;
				}
			}
		}
		
		if ($res) {
			//If there's no controler or action defined, set them "home" and "index".
			if (sizeof($command_array) == 0) {
				$command_array[0] = "home";
				$command_array[1] = "index";
			}
			else if (sizeof($command_array) == 1) {
				$command_array[1] = "index";
			}
			
			//If there's any parameters, separate it from the command array.
			$parameters = array_slice($command_array, 2);

			//Creates the command.
			$this->command = new RoutingCommand($lang_url, $command_array[0], $command_array[1], $parameters, $get_params_url);
		}
		
		return $res;
	}
 	
	/**
	 * Gets the current rounting command
	 * @return RountingCommand
	 */
	public function get_routing_command() {
		return $this->command;
	}
}