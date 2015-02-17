<?php
error_reporting(E_ALL & ~ E_STRICT & ~ E_DEPRECATED);
ini_set('default_charset', 'UTF-8');	
session_start();

require('config.php');
require('libs/includes.php');

$interpreter = new Intentor\PhpMVC\UrlInterpreter();
if ($interpreter->interpret($_SERVER['REQUEST_URI'])) {
	$command = $interpreter->get_routing_command();
	
	if ((strpos(CONTROLLERS_NO_LANG, $command->get_controller()) !== false) || $command->has_language()) {
		$dispatcher = new Intentor\PhpMVC\CommandDispatcher($command);
		$dispatcher->dispatch();
	} else {
		header('Location: '.$command->get_command_url());
	}
} else {
	header('Location: '.get_url(URL_HTTP404));
}