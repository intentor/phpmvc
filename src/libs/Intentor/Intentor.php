<?php
	require_once dirname(__FILE__) . '/NotORM/NotORM.php';
	require_once dirname(__FILE__) . '/Addendum/annotations.php';
 	require_once dirname(__FILE__) . '/PHPMailer/PHPMailerAutoload.php';
 	foreach (glob(dirname(__FILE__).'/PhpMVC/*.php') as $filename) {
 		require_once $filename;
 	}
	foreach (glob(dirname(__FILE__).'/Utilities/*.php') as $filename) {
		require_once $filename;
	}	
	foreach (glob(dirname(__FILE__).'/Utilities/Annotations/*.php') as $filename) {
		require_once $filename;
	}
	foreach (glob(dirname(__FILE__).'/Utilities/NotORM/*.php') as $filename) {
		require_once $filename;
	}