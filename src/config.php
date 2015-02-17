<?php
//BASIC CONFIGURATIONS	
define('SITE_ENCODING'				, 'UTF-8');	
define('PROTOCOL'					, (isset($_SERVER['HTTPS']) ? 'https://' : 'http://'));		//Protocol type.
define('URL_BASE'					, PROTOCOL.'phpmvc.intentor.com.br:85/');					//Base URL for the app. Always include '/' at the end of the string.
define('DIRECTORY_ROOT'				, '/');														//Base directory in which the application is running.
define('LAYOUT'						, '_layout.php');											//Main layout for views.
define('URL_LOGIN'					, 'login/');												//Login URL.
define('URL_HTTP404'				, 'error/404/');											//HTTP error 404 URL.
define('SYSTEM_VERSION'				, '14.X');													//System version.
//LANGUAGE
define('DEFAULT_LANGUAGE'			, 'pt-BR');													//Default language.
define('USE_SHORT_ISO_CODE'			, true);													//Indicates if the shorthest language ISO Code (two letters) will be used.
define('CONTROLLERS_NO_LANG'		, '');														//Controllers that do not need to be redirected. Separate them by commas.
//E-MAIL CONFIGURATIONS
define('SMTP_HOST'                  , 'smtp.trixter.com.br');									//SMTP host. E. g.: smtp.mydomain.com
define('SMTP_SECURITY'            	, '');														//SMTP security type, if there's any. Options: '', 'ssl', 'tls'.
define('SMTP_PORT'                  , 587);														//SMTP port, usually 25.
define('SMTP_USER'                  , 'noreply@trixter.com.br');								//SMTP authentication user name, if there's any.
define('SMTP_PASSWORD'              , 'g92Hy4');												//SMTP authentication password, if there's any.
define('SMTP_DEBUG'					, false);													//Indicates if the debug is enabled when sending mail.
define('EMAIL_FROM'                 , 'noreply@trixter.com.br'); 								//SMTP e-mail sender's e-mail.
//DATABASE CONFIGURATIONS
define('NOTORM_CACHE'				, 'notorm.dat');											//Path to NotORM's cache file.
define('TABLE_PREFIX'				, 'mvc_');													//Table's prefix.	
define('STRUCTURE_CONVENTION_CLASS'	, 'StructureConvention');									//Name of the class which handles structure convention.
define('CONN_HOST'					, 'localhost');												//Database server.	
define('CONN_DB'					, 'phpmvc');												//Database name.
define('CONN_USER'					, 'root');												//Username.
define('CONN_PASS'					, 'hk1234');												//Password.
//PAGER
define('PAGE_SIZE'					, 20);														//Table paging size.